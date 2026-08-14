# 🧠 Alzheimer's Disease Detection from MRI Scans

A deep learning graduation project that detects **Alzheimer's disease** from **brain MRI scans** using a **Siamese Network** trained with **one-shot learning**. The system compares a new MRI scan against a reference gallery of labeled scans and classifies it as *Mild Demented* or *Not Demented*.

<p align="center">
  <img src="https://img.shields.io/badge/Python-3.9-blue?logo=python&logoColor=white" alt="Python">
  <img src="https://img.shields.io/badge/TensorFlow-2.12-orange?logo=tensorflow&logoColor=white" alt="TensorFlow">
  <img src="https://img.shields.io/badge/Keras-2.12-red?logo=keras&logoColor=white" alt="Keras">
  <img src="https://img.shields.io/badge/Flask-2.3-black?logo=flask&logoColor=white" alt="Flask">
  <img src="https://img.shields.io/badge/XGBoost-baseline-green?logo=xgboost&logoColor=white" alt="XGBoost">
  <img src="https://img.shields.io/badge/PHP-Web%20App-777BB4?logo=php&logoColor=white" alt="PHP">
</p>

---

## 📌 Project Overview

Alzheimer's disease is a progressive neurological disorder and the most common cause of dementia. Early diagnosis is critical for better management of the disease. This project builds an end-to-end system that:

1. **Trains a Siamese Network** on MRI brain scan pairs to learn *"are these two scans from the same class?"*
2. **Classifies new scans** using one-shot learning — a majority vote over similarity scores against reference images from each class
3. **Serves predictions** through a REST API (Flask) and an interactive demo (Streamlit)
4. **Provides a full web platform** (PHP + MySQL) for doctors/patients: account registration, login, MRI upload, and patient history

> One-shot learning is especially useful here because medical datasets are small and new classes (e.g., different MRI machines or hospitals) appear frequently without needing model retraining.

---

## ✨ Features

| Feature | Description |
|---------|-------------|
| 🧬 **Siamese Network** | Twin CNN architecture with Euclidean distance head |
| 🎯 **One-shot classification** | Classify without retraining — compare against reference gallery |
| 📊 **XGBoost baseline** | GLCM/texture feature extraction + gradient boosting comparison |
| 🌐 **Flask REST API** | `POST /classify` — upload an MRI, get a JSON prediction |
| 🖥️ **Streamlit demo** | Quick interactive demo with confidence metric |
| 🌍 **PHP web application** | Login/registration, MRI upload, patient management, search & history |
| 🔐 **Secure code** | Prepared statements (SQL injection protection), password hashing, file validation |

---

## 🧠 How It Works

### 1. Data Preparation
MRI scans (128×128) from the [Alzheimer's MRI dataset](https://www.kaggle.com/datasets/sachinkumar413/alzheimer-mri-dataset) are preprocessed and converted into **image pairs**:

- 64 reference images (32 per class)
- Every pair is labeled `1` if both images share the same class, else `0`
- → 2,016 training pairs

### 2. Siamese Network
Two identical CNN branches (shared weights) extract features from each image in a pair. The features are compared using a **Euclidean distance** layer followed by dense layers and a softmax output:

```
Image A ──► CNN (shared weights) ──┐
                                   ├──► Euclidean Distance ──► Dense layers ──► Softmax (similar / not similar)
Image B ──► CNN (shared weights) ──┘
```

### 3. One-shot Prediction
A new scan is paired with **every reference image**. The model predicts similarity for each pair, and the class with the majority of similar references wins:

```
Query MRI + MildDemented refs  → similarity scores
Query MRI + NotDemented refs   → similarity scores
Result: class with the most "similar" votes + confidence
```

---

## 📈 Results

### Siamese Network (final model — `model/Siamese.h5`)

| Metric | Value |
|--------|-------|
| Training Accuracy | **100%** |
| Validation Accuracy | **98.51%** |
| Best Validation Loss | **0.0572** |
| Training pairs | 1,612 |
| Validation pairs | 404 |
| Optimizer | Adam (lr=0.001) |
| Early stopping | patience=3 (stopped at epoch 25/50) |

### XGBoost Baseline (GLCM texture features)

| Metric | Value |
|--------|-------|
| Training Accuracy | **95.16%** |
| Test Accuracy | **84.16%** |

The Siamese network **outperforms the classical ML baseline by +14%** on test data, demonstrating the power of learned deep features over hand-crafted features for medical images.

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Model | TensorFlow / Keras — Siamese Network, XGBoost |
| Image processing | Pillow, scikit-image (GLCM, Sobel) |
| API | Flask, Flask-CORS |
| Demo | Streamlit |
| Web app | PHP, MySQL, Bootstrap |
| Training | Google Colab (GPU) |
| Deployment | Any server with Python 3.9+ |

---

## 📂 Project Structure

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
│   └── reference_images/                   # Reference gallery (one-shot)
│       ├── MildDemented/
│       └── NotDemented/
├── web/                                    # PHP web application
│   ├── home.php / about.php / contact.php  # Pages
│   ├── loginn.php                          # Login & registration
│   ├── pathiont/                           # Patient management (list/edit/profile)
│   └── css/ js/ img/                       # Assets
├── database/
│   └── alzhimar.sql                        # MySQL schema
├── requirements.txt
└── README.md
```

---

## 🚀 Getting Started

### 1. Streamlit demo (easiest)

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
| `/classify` | POST | Multipart form field `image` → `{"prediction": "...", "confidence": ...}` |

```bash
curl -X POST -F "image=@mri.jpg" http://localhost:5000/classify
```

### 3. Web application (PHP + MySQL)

1. Install [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP)
2. Import `database/alzhimar.sql` into phpMyAdmin
3. Copy the `web/` folder into `htdocs/`
4. Start Apache & MySQL, then open `http://localhost/web/`

---

## 🔮 Future Work

- 🧬 Train on the full 4-class Alzheimer's MRI dataset (Mild, Moderate, Very Mild, Non-Demented)
- 🏥 Deploy the API to the cloud (Railway / Render / AWS)
- 📈 Add attention mechanisms or transfer learning (ResNet / EfficientNet backbones)
- 🎯 Improve confidence calibration for clinical-grade decisions

---

## 👨‍💻 Author

**[Yousef Ashraf](https://github.com/yousefashraf-dev)** — Computer Science graduation project

---

## 📄 License

This project is for educational purposes. The MRI dataset belongs to its respective authors (Kaggle).
