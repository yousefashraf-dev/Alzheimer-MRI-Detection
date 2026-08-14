"""Flask REST API for Alzheimer's disease detection.

Run:
    python api.py

Endpoints:
    GET  /health     -> service status
    POST /classify   -> upload an MRI image (multipart field "image")
"""

import os

from flask import Flask, jsonify, request
from flask_cors import CORS
from PIL import Image

from classifier import SiameseClassifier

ALLOWED_EXTENSIONS = {"jpg", "jpeg", "png", "webp"}

app = Flask(__name__)
CORS(app)

classifier = None


def _init_classifier():
    global classifier
    if classifier is None:
        classifier = SiameseClassifier()
    return classifier


def _is_allowed(filename):
    return "." in filename and filename.rsplit(".", 1)[1].lower() in ALLOWED_EXTENSIONS


@app.route("/health", methods=["GET"])
def health():
    return jsonify({"status": "ok"})


@app.route("/classify", methods=["POST"])
def classify():
    try:
        if "image" not in request.files:
            return jsonify({"error": "Missing 'image' field in multipart form data"}), 400

        file = request.files["image"]
        if file.filename == "" or not _is_allowed(file.filename):
            return jsonify({"error": "Invalid image file"}), 400

        image = Image.open(file.stream)
        result = _init_classifier().predict(image)
        return jsonify(result)

    except Exception as exc:
        return jsonify({"error": f"Classification failed: {exc}"}), 500


if __name__ == "__main__":
    port = int(os.environ.get("PORT", 5000))
    app.run(host="0.0.0.0", port=port)