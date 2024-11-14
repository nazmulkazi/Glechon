<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About Glechon

Glechon is a web app developed to annotate and grade student responses from quizzes and exams to generate data for NLP research, such as automated grading and misconception detection.

![Dashboard](https://oxiago.com/github/readme_images/Glechon/glechon-dashboard.png)

We take advantage of the Laravel framework to develop the web app. We prioritized versatility in its design, ensuring its adaptability to various courses. The dashboard, presented in a tabular format, offers an overview of datasets, with each dataset row containing essential metadata such as the course name, activity type, year, semester, response count, and an accessible dropdown menu. The dropdown menu, tailored to user roles, offers a range of actions, including viewing, editing, annotating, exporting, and deleting datasets. Additionally, users have the option to import datasets from files or URLs. An independent web app is currently under development to collect student responses digitally. Importing datasets via URLs will allow us to import student responses directly from another server in the future.

![Annotation Page - Selecting Label](https://oxiago.com/github/readme_images/Glechon/glechon-annotate.png)

![Annotation Page - Selecting Context](https://oxiago.com/github/readme_images/Glechon/glechon-annotate-context.png)

Within the annotation interface, all responses from a dataset are displayed, each presented in an individual table complete with a unique identifier. Responses may consist of multiple sentences, each enumerated and displayed in separate rows. Each row features two multi-tag boxes---one for labels and another for selecting sentences providing context for the chosen label. In anticipation of numerous labels, both multi-tag boxes incorporate search functionality. Annotators can apply multiple labels to a sentence and specify context sentences per label. For convenience, each response includes a dedicated save button, allowing annotators to pause and easily track their progress. The input boxes are color-coded, with amber indicating *never annotated*, red for *unsaved*, and green for *successfully saved*.

![Inviting users and login](https://oxiago.com/github/readme_images/Glechon/glechon-user-invite.png)

To ensure security, the application is safeguarded behind a login page, with all communication encrypted. Only administrators have the ability to invite new users by providing their email addresses in the system. An automated email containing a unique code is sent to the provided address to prevent the creation of malicious accounts. After a user signs up, administrators have the authority to assign roles and customize permissions. User permissions can be tailored to include specific actions within the app. Furthermore, we have implemented role-based user permissions, simplifying the assignment of permissions to common user roles, such as annotators, for efficient and streamlined access control.

![User Access Control](https://oxiago.com/github/readme_images/Glechon/glechon-user-control.png)

Users with appropriate permissions have the capability to export any dataset in JSON format, a widely recognized and supported file format. The exported file encompasses dataset metadata, responses segmented into sentences, and annotations for each sentence from every annotator, all organized in a standardized format. The application also incorporates API support, providing users with tokens. This allows users to employ the API through programming scripts, enabling direct data retrieval from the server and seamless integration into their model training.

## Publication

Nazmul Kazi. Automated short-answer grading and misconception detection
using large language models. Master's thesis, University of North Florida, Jacksonville, FL, December 2023. <a href="https://digitalcommons.unf.edu/etd/1234">https://digitalcommons.unf.edu/etd/1234</a>

## License

This software is licensed for non-commercial use only. Researchers and students may use it for academic purposes with prior permission and should share any improvements or modifications they make. The author retains the right to review and decide on the inclusion of any contributed changes. Commercial use, distribution, or sublicensing without explicit permission is strictly prohibited. Please check the LICENSE file for more details.