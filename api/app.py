"""Streamlit demo app for Alzheimer's disease detection.

Run:
    streamlit run app.py
"""

import os

import streamlit as st
from PIL import Image

from classifier import SiameseClassifier


@st.cache_resource
def get_classifier():
    return SiameseClassifier()


st.set_page_config(page_title="Alzheimer MRI Detection")
st.title("Alzheimer's Disease Detection")
st.caption("Siamese Network (one-shot learning) on brain MRI scans")

uploaded_file = st.file_uploader("Upload an MRI brain scan", type=["jpg", "jpeg", "png", "webp"])

if uploaded_file is not None:
    image = Image.open(uploaded_file)
    st.image(image, caption="Uploaded MRI scan", use_container_width=True)

    with st.spinner("Classifying..."):
        result = get_classifier().predict(image)

    st.success(f"**Prediction: {result['prediction']}**")
    st.metric("Confidence", f"{result['confidence'] * 100:.1f}%")
