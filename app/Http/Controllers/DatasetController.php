<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\Response;
use App\Models\Annotation;

use Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DatasetController extends Controller
{
    public function __construct()
    {
        // Authorize routes
        $this->middleware('authorize:create-dataset')->only(['create', 'import', 'store', 'edit', 'update']);
        $this->middleware('authorize:annotate-dataset')->only(['annotate', 'storeAnnotation']);
        $this->middleware('authorize:statistics-dataset')->only(['statistics']);
        $this->middleware('authorize:export-dataset')->only(['export']);
        $this->middleware('authorize:destroy-dataset')->only(['destroy']);
    }
    
    /**
     * List the datasets with their metadata and a dropdown menu of available actions for the user.
     * Currently, this is the dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $actions = [
            'show' => 'View',
            'annotate' => 'Annotate',
            'edit' => 'Edit',
            'statistics' => 'Statistics',
            'export' => 'Export',
            'destroy' => 'Delete',
        ];
        
        // if (auth()->user()->can('show-article')) {
        
        // filter actions for non-admin users
        if (Auth::user()->role !== 'admin') {
            $permitted_actions = array_merge(['show'], Auth::user()->permissions->dataset ?? []);
            $actions = array_filter($actions, fn($k) => in_array($k, $permitted_actions), ARRAY_FILTER_USE_KEY);
        }
        
        return Inertia::render('Dataset/Index', [
            'datasets' => Dataset::leftJoin('users', 'datasets.creator_id', '=', 'users.id')
                            ->orderBy('course', 'asc')
                            ->orderBy('activity', 'asc')
                            ->orderBy('year', 'desc')
                            ->orderBy('semester', 'asc')
                            ->orderBy('name', 'asc')
                            ->selectRaw('datasets.id, datasets.course, datasets.activity, datasets.year, datasets.semester, datasets.name, datasets.num_responses, JSON_LENGTH(labels) as num_labels, users.name as creator')
                            ->get(),
            'actions' => $actions,
        ]);
    }

    
    
    /**
     * Show the form for creating a new dataset.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    
    /**
     * Validates and sorts dataset labels.
     *
     * @param   object  $labels  Dataset labels.
     * @return  object|string    An array containing the sorted labels or an error message.
     */
    private function validateAndFormatLabels(object $labels) {
        // Check if labels object is missing
        if (is_null($labels)) {
            return "Dataset labels are missing.";
        }
        
        try {
            // Convert labels object to an array
            $labelsArray = (array) $labels;
            
            // Check if labels object is empty
            if (empty($labelsArray)) {
                return "Dataset labels object cannot be empty.";
            }
            
            $numLabels = count($labelsArray);
            
            // Convert keys to lowercase
            $lowercaseLabels = array_change_key_case($labelsArray, CASE_LOWER);
            
            // Check for duplicate keys after case-insensitive conversion
            if (count($lowercaseLabels) < $numLabels) {
                return "Each key within the dataset's labels object must be distinct in a case-insensitive manner, ensuring that multiple keys do not map to the same lowercase text value.";
            }
            
            return (object) $lowercaseLabels;
        } catch (\Exception $e) {
            return "Unable to parse dataset labels.";
        }
    }
    
    /**
     * Store a new dataset in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('file')) {
            $request->validate([
                'file' => ['file', 'mimes:json'],
            ]);
            
            $file = $request->file('file');
            $data = json_decode($file->get());
            
            DB::beginTransaction();

            try {
                $dataset = new Dataset();
                $dataset->creator_id = Auth::id();
                $dataset->course = $data->course;
                $dataset->activity = $data->activity;
                $dataset->year = $data->year;
                $dataset->semester = $data->semester;
                $dataset->name = $data->name ?? NULL;
                $dataset->num_responses = $data->num_responses ?? count((array) $data->responses);
                $labels = $this->validateAndFormatLabels($data->labels);
                if (is_string($labels)) {
                    throw new \Exception($labels);
                } else {
                    $dataset->labels = $labels;
                }
                $dataset->save();
                
                foreach ($data->responses as $response_id => $sentences){
                    foreach ($sentences as $index => $sentence) {
                        $response = new Response();
                        $response->dataset_id = $dataset->id;
                        $response->response_id = $response_id;
                        $response->sentence_index = $index;
                        $response->sentence = $sentence;
                        $response->save();
                    }
                }
                
                foreach ($data->annotations as $response_id => $response_annotations) {
                    foreach ($response_annotations as $annotator_id => $annotator) {
                        $annotation = new Annotation();
                        $annotation->dataset_id = $dataset->id;
                        $annotation->response_id = $response_id;
                        $annotation->annotator_id = $annotator_id;
                        $annotation->labels = (object) $annotator->labels;
                        $annotation->context_sentence_indices = (object) $annotator->context;
                        $annotation->save();
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('dataset.import')->withErrors([
                    'file' => $e->getMessage()
                ]);
            }

            return redirect()->route('dataset.import');
        } else if ($request->has('link')) {
            // import from link
        } else {
            // create dataset
        }
    }

    /**
     * Show: Display the specified dataset with the user's annotations, if any.
     * Annotate: Display the specified dataset with forms to annotate the responses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dataset  $dataset
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Dataset $dataset)
    {
        $annotate = \Route::currentRouteName() == 'dataset.annotate';
        
        $responses = $dataset->responses()
            ->select('response_id', 'sentence_index', 'sentence')
            ->get()
            ->groupBy('response_id')
            ->map(fn ($group) => $group->sortBy('sentence_index')->pluck('sentence')->toArray())
            ->toArray();
        $annotator_id = !$annotate && $request->has('annotator_id') && Auth::user()->role === 'admin' ? intval($request->input('annotator_id')) : Auth::id();
        $annotations = $dataset->annotations($annotator_id)
           ->select('response_id', 'labels', 'context_sentence_indices')
           ->get();
        
        $props = [
            'dataset' => $dataset,
            'responses' => $responses,
            'annotations' => [
                'labels' => $annotations->pluck('labels', 'response_id'),
                'context_sentence_indices' => $annotations->pluck('context_sentence_indices', 'response_id'),
            ],
        ];
        
        if (!$annotate && Auth::user()->role === 'admin') {
            $annotators = DB::table('annotations')
                ->select(DB::raw('DISTINCT annotations.annotator_id, users.name'))
                ->join('users', 'annotations.annotator_id', '=', 'users.id')
                ->where('annotations.dataset_id', '=', $dataset->id)
                ->orderBy('users.name', 'asc')
                ->pluck('name', 'annotator_id')
                ->toArray();
            $props['annotators'] = $annotators;
            $props['selected_annotator'] = $annotator_id;
        }
        
        return Inertia::render($annotate ? 'Dataset/Annotate' : 'Dataset/Show', $props);
    }

    /**
     * Show the form for editing the dataset metadata.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return \Illuminate\Http\Response
     */
    public function edit(Dataset $dataset)
    {
        return Inertia::render('Dataset/Edit', [
            'dataset' => $dataset
        ]);
    }

    /**
     * Update the metadata of the specified dataset in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dataset  $dataset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dataset $dataset)
    {
        //
    }
    
    /**
     * Remove the specified dataset from storage.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dataset $dataset)
    {
        //
    }
    
    /**
     * Store or update the given annotation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $dataset_id
     * @param  int  $response_id
     * @return \Illuminate\Http\Response
     */
    public function storeAnnotation(Request $request, int $dataset_id, int $response_id)
    {
        $annotation = Annotation::updateOrCreate([
            'dataset_id' => $dataset_id,
            'response_id' => $response_id,
            'annotator_id' => Auth::id()
        ], [
            'labels' => $request->input('labels'),
            'context_sentence_indices' => $request->input('context_sentence_indices')
        ]);
        
        if ($annotation->wasRecentlyCreated || $annotation->wasChanged(['labels', 'context_sentence_indices'])) {
            return response()->json([
                'status' => 'success'
            ]);
        }
        
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to save! Please try again!'
        ]);
    }
    
    /**
     * Show the form for importing a dataset.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        return Inertia::render('Dataset/Import');
    }
    
    /**
     * Export the specified dataset.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return \Illuminate\Http\Response
     */
    public function export(Dataset $dataset)
    {
        // Gather and format information to export
        $data = [
            'course' => $dataset->course,
            'activity' => $dataset->activity,
            'year' => $dataset->year,
            'semester' => $dataset->semester,
            'name' => $dataset->name,
            'num_responses' => $dataset->num_responses,
            'labels' => $dataset->labels,
            'responses' => $dataset->responses()
                ->get()
                ->groupBy('response_id')
                ->map(function ($group) {
                    return $group
                        ->sortBy('sentence_index')
                        ->pluck('sentence');
                }),
            'annotator_ids' => $dataset->annotations()
                ->select('annotator_id')
                ->distinct()
                ->pluck('annotator_id')
                ->toArray(),
            'annotations' => $dataset->annotations()
                ->get()
                ->groupBy('response_id')
                ->map(function ($responseAnnotations) {
                    return $responseAnnotations
                        ->keyBy('annotator_id')
                        ->map(function ($annotator) {
                            return [
                                'labels' => $annotator->labels,
                                'context' => $annotator->context_sentence_indices
                            ];
                    });
                })
        ];
        
        return response()->stream(
            function () use ($data) {
                echo json_encode($data);
            },
            200,
            [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="dataset_' . $dataset->id . '.json"'
            ]
        );
    }
    
    /**
     * Show statistics of the specified dataset.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return \Illuminate\Http\Response
     */
    public function statistics(Dataset $dataset)
    {
        /**
         * ctx -> context
         * csi -> context sentence indices
         * dist_label_res : label distribution across responses
         * dist_label_snt : label distribution across sentences
         * dist_num_ctx   : number of context sentences per label
         * num_res_mc     : number of responses with misconception
         */
        
        // retrieve information from the database, calculate and returns statistics for a given dataset
        
        // retrieve and returned cached statistics, if a cache exists and there is no new annotation
        $latest_annotation_timestamp = DB::table('annotations')
            ->where('dataset_id', '=', $dataset->id)
            ->max('updated_at');
        
        if ($dataset->statistics !== NULL && $dataset->statistics_updated_at > $latest_annotation_timestamp) {
            return Inertia::render('Dataset/Statistics', [
                'dataset' => $dataset,
                'statistics' => $dataset->statistics
            ]);
        }
        
        // fetch the annotations and the number of response annotated for each annotator of the dataset
        $subquery = DB::table('annotations')
            ->select('annotator_id', DB::raw('COUNT(*) as num_annotated'), DB::raw('JSON_ARRAYAGG(labels) as labels'), DB::raw('JSON_ARRAYAGG(context_sentence_indices) as csi'))
            ->where('dataset_id', '=', $dataset->id)
            ->groupBy('annotator_id');
        
        // fetch the name of each annotator along with previous information
        $rows = DB::table(DB::raw("({$subquery->toSql()}) as annotations"))
            ->mergeBindings($subquery)
            ->leftJoin('users', 'users.id', '=', 'annotations.annotator_id')
            ->select('users.name', 'annotations.num_annotated', 'annotations.labels', 'annotations.csi')
            ->get();
        
        // get the list of unique labels of the dataset
        $labels_uniq = array_keys(get_object_vars($dataset->labels));
        
        $statistics = [];
        
        // loop through each annotator's data
        foreach ($rows as $row) {
            // these arrays keep track of label counts for the annotator at the response and sentence level, respectively.
            $dist_label_res = $dist_label_snt = $dist_num_ctx = array_fill_keys($labels_uniq, 0);
            $dist_mlabel_res = array();
            $dist_mlabel_snt = array();
            
            $csi = json_decode($row->csi, true);
            $num_res_mc = 0;
            
            // loop through each response
            foreach (json_decode($row->labels) as $res_idx => $labels_res) {
                // this array stores all the labels of current response
                $labels_res_all = [];
                
                // loop through each sentence in the response and add its labels to the overall list
                foreach ($labels_res as $snt_idx => $labels_snt) {
                    foreach ($labels_snt as $label) {
                        $labels_res_all[] = $label;
                    }
                    
                    // check for multi-label annotations
                    if (count($labels_snt) > 1) {
                        $label = '#'.count($labels_snt);
                        $labels_res_all[] = $label;
                    }
                    
                    // count the number of context sentences of this sentence, if any
                    if (array_key_exists($res_idx, $csi) && array_key_exists($snt_idx, $csi[$res_idx])) {
                        foreach ($csi[$res_idx][$snt_idx] as $label => $indices) {
                            $dist_num_ctx[$label] += count($indices);
                        }
                    }
                }
                
                // increment the number of responses with misconception
                if (count($labels_res_all) > 0) {
                    $num_res_mc++;
                }
                
                // update the label counts
                foreach (array_count_values($labels_res_all) as $label => $count) {
                    if (substr($label, 0, 1) === '#') {
                        // add a key for this new label to the count lists if it doesn't exist yet
                        if(!isset($dist_mlabel_res[$label])){
                            $dist_mlabel_res[$label] = $dist_mlabel_snt[$label] = 0;
                        }
                        $dist_mlabel_res[$label]++;
                        $dist_mlabel_snt[$label] += $count;
                    } else {
                        $dist_label_res[$label]++;
                        $dist_label_snt[$label] += $count;
                    }
                }
            }
            
            // add the annotator's statistics to the overall statistics array
            $statistics[] = [
                'name' => $row->name,
                'num_annotated' => $row->num_annotated,
                'num_res_mc' => $num_res_mc,
                'dist_label_res' => $dist_label_res,
                'dist_label_snt' => $dist_label_snt,
                'dist_mlabel_res' => $dist_mlabel_res,
                'dist_mlabel_snt' => $dist_mlabel_snt,
                'dist_num_ctx' => $dist_num_ctx,
            ];
        }
        
        // cache calculated statistics
        $dataset->update([
            'statistics' => $statistics,
            'statistics_updated_at' => now()
        ]);
        
        // render the statistics page using Inertia.js and pass in the dataset and statistics data
        return Inertia::render('Dataset/Statistics', [
            'dataset' => $dataset,
            'statistics' => $statistics
        ]);
    }
}
