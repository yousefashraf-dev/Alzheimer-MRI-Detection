"""Siamese Network classifier for Alzheimer's disease detection from MRI scans.

The model was trained in one-shot learning fashion: instead of classifying a
single image, it compares the query image against a set of reference images
from each class and returns the class of the most similar references.
"""

import os

import numpy as np
import pandas as pd
from PIL import Image
from sklearn.preprocessing import OneHotEncoder
from tensorflow.keras.models import load_model

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_PATH = os.path.join(BASE_DIR, "..", "model", "Siamese.h5")
DATA_DIR = os.path.join(BASE_DIR, "reference_images")

IMG_SIZE = 128
MAX_IMAGES_PER_CLASS = 32


def _load_reference_images(data_dir=DATA_DIR):
    """Load up to MAX_IMAGES_PER_CLASS reference images per class."""
    images, labels = [], []
    for class_name in sorted(os.listdir(data_dir)):
        class_dir = os.path.join(data_dir, class_name)
        if not os.path.isdir(class_dir):
            continue
        for file_name in sorted(os.listdir(class_dir))[:MAX_IMAGES_PER_CLASS]:
            image = Image.open(os.path.join(class_dir, file_name))
            image = image.convert("RGB").resize((IMG_SIZE, IMG_SIZE))
            images.append(np.array(image) / 255.0)
            labels.append(class_name)
    return images, labels


class SiameseClassifier:
    """One-shot classifier that votes on similarity between images."""

    def __init__(self, model_path=MODEL_PATH, data_dir=DATA_DIR):
        self.model = load_model(model_path)
        self.images_array, self.labels = _load_reference_images(data_dir)
        if not self.images_array:
            raise ValueError(f"No reference images found in {data_dir}")
        self.encoder = OneHotEncoder()
        self.encoder.fit(np.zeros(len(self.images_array), dtype=int).reshape(-1, 1))

    @staticmethod
    def preprocess(image):
        """Resize and normalize a PIL image to the model input format."""
        image = image.convert("RGB").resize((IMG_SIZE, IMG_SIZE))
        return np.array(image) / 255.0

    def predict(self, image):
        """Classify a query image using a majority vote over similar references."""
        query = self.preprocess(image)
        references = np.asarray(self.images_array)
        pairs = [references, np.repeat(np.asarray([query]), len(references), axis=0)]
        probabilities = self.model.predict(pairs, verbose=0)

        votes = [
            (label, confidence)
            for label, confidence in zip(self.labels, probabilities[:, 1])
        ]
        votes.sort(key=lambda item: item[1], reverse=True)

        similar = [label for label, conf in votes if conf >= 0.5]
        if not similar:
            return {"prediction": votes[0][0], "confidence": float(votes[0][1])}

        counts = pd.Series(similar).value_counts()
        best_label = counts.idxmax()
        confidence = float(counts[best_label] / len(similar))
        return {"prediction": best_label, "confidence": confidence}