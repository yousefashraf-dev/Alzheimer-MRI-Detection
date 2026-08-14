# Alzheimer's Disease Detection from MRI Scans

A graduation project that detects Alzheimer's disease from brain MRI scans using a **Siamese Network** with **one-shot learning**. The system compares a new MRI scan against a set of labeled reference scans and classifies it as *Mild Demented* or *Not Demented*.

## Project Overview

Alzheimer's disease is a progressive neurological disorder and the most common cause of dementia. Early diagnosis plays an important role in managing the disease. This project covers the full pipeline:

1. Training a Siamese Network on pairs of MRI scans to learn whether two scans belong to the same class
2. Classifying new scans using one-shot learning: a majority vote over similarity scores against reference images from each class
3. Serving predictions through a REST API (Flask) and an interactive demo (Streamlit)
4. A web platform (PHP + MySQL) where users can register, log in, upload MRI scans, and view patient history

One-shot learning was chosen because medical datasets are usually small, and the model can classify new classes (e.g. scans from a different hospital or machine) without retraining.

## Features

| Feature | Description |
|---------|-------------|
| Siamese Network | Twin CNN architecture with a Euclidean distance head |
| One-shot classification | Classify without retraining by comparing against a reference gallery |
| XGBoost baseline | GLCM texture feature extraction + gradient boosting for comparison |
| Flask REST API | `POST /classify` — upload an MRI image and get a JSON prediction |
| Streamlit demo | Simple interactive demo that also shows the confidence score |
| PHP web application | Login/registration, MRI upload, patient management, search and history |
| Secure code | Prepared statements, password hashing, and file type validation |

## How It Works

### 1. Data Preparation

MRI scans are resized to 128x128 and grouped into image pairs:

- 64 reference images (32 from each class)
- Each pair is labeled `1` if both images are from the same class, otherwise `0`
- This produces 2,016 training pairs

### 2. Siamese Network

Two identical CNN branches share the same weights and extract features from each image in a pair. The features are compared using a Euclidean distance layer followed by dense layers and a softmax output:

```
Image A -> CNN (shared weights) ---+
                                   +--> Euclidean Distance -> Dense layers -> Softmax
Image B -> CNN (shared weights) ---+
```

### 3. One-shot Prediction

A new scan is paired with every reference image. The model predicts a similarity score for each pair, and the class with the most similar references wins:

```
Query MRI + MildDemented refs  -> similarity scores
Query MRI + NotDemented refs   -> similarity scores
Result: class with the most "similar" votes, plus a confidence score
```

## Results

### Siamese Network (`model/Siamese.h5`)

| Metric | Value |
|--------|-------|
| Training accuracy | 100% |
| Validation accuracy | 98.51% |
| Best validation loss | 0.0572 |
| Training pairs | 1,612 |
| Validation pairs | 404 |
| Optimizer | Adam (lr=0.001) |
| Early stopping | patience=3 (stopped at epoch 25/50) |

### XGBoost Baseline (GLCM texture features)

| Metric | Value |
|--------|-------|
| Training accuracy | 95.16% |
| Test accuracy | 84.16% |

The Siamese network performed better than the XGBoost baseline on test data (98.51% vs 84.16%), which shows that learned deep features work better than hand-crafted features for this task.

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Model | TensorFlow / Keras (Siamese Network), XGBoost |
| Image processing | Pillow, scikit-image (GLCM, Sobel) |
| API | Flask, Flask-CORS |
| Demo | Streamlit |
| Web app | PHP, MySQL, Bootstrap |
| Training | Google Colab |

## Project Structure

```
Alzheimer-MRI-Detection/
├── notebook/
│   └── Siamese_Alzheimer_Detection.ipynb   # Training notebook (Colab)
├── model/
│   └── Siamese.h5                          # Trained Siamese network
├── api/
│   ├── classifier.py                       # Shared Siamese classifier logic
│   ├── api.py                              # Flask REST API
│   ├── app.py                              # Streamlit demo
│   └── reference_images/                   # Reference gallery for one-shot
│       ├── MildDemented/
│       └── NotDemented/
├── web/                                    # PHP web application
│   ├── home.php / about.php / contact.php  # Main pages
│   ├── loginn.php                          # Login & registration
│   ├── pathiont/                           # Patient management (list/edit/profile)
│   └── css/ js/ img/                       # Assets
├── database/
│   └── alzhimar.sql                        # MySQL schema
├── requirements.txt
└── README.md
```

## Getting Started

### 1. Streamlit demo

```bash
pip install -r requirements.txt
cd api
streamlit run app.py
```

### 2. Flask REST API

```bash
cd api
python api.py
```

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/health` | GET | Service status |
| `/classify` | POST | Multipart form field `image` -> `{"prediction": "...", "confidence": ...}` |

```bash
curl -X POST -F "image=@mri.jpg" http://localhost:5000/classify
```

### 3. Web application (PHP + MySQL)

1. Install XAMPP (Apache + MySQL + PHP)
2. Import `database/alzhimar.sql` into phpMyAdmin
3. Copy the `web/` folder into `htdocs/`
4. Start Apache and MySQL, then open `http://localhost/web/`

## Future Work

- Train on the full 4-class Alzheimer's MRI dataset (Mild, Moderate, Very Mild, Non-Demented)
- Deploy the API to the cloud (Railway / Render / AWS)
- Try transfer learning backbones (ResNet / EfficientNet)
- Improve confidence calibration for clinical use

## Author

Yousef Ashraf - Computer Science graduate project

## License

This project is for educational purposes. The MRI dataset belongs to its original authors on Kaggle.