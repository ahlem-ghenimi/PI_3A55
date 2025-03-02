from flask import Flask, request, jsonify
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing import image
import numpy as np
from PIL import Image

app = Flask(__name__)

# Load your pre-trained model
try:
    model = load_model('path/to/your/product_health_model.h5')  # Replace with your model path
    print("Model loaded successfully!")
except Exception as e:
    print(f"Error loading model: {e}")
    model = None

# Define image preprocessing function
def preprocess_image(img):
    try:
        # Convert to RGB if necessary
        if img.mode != "RGB":
            img = img.convert("RGB")

        # Resize the image to the expected input size of your model
        img = img.resize((224, 224))  # Adjust size based on your model's input

        # Convert the image to a numpy array
        img_array = image.img_to_array(img)

        # Expand dimensions to match the input shape of the model
        img_array = np.expand_dims(img_array, axis=0)

        # Normalize pixel values
        img_array = img_array / 255.0

        return img_array
    except Exception as e:
        print(f"Error preprocessing image: {e}")
        return None

# Define the analyze route
@app.route('/analyze', methods=['POST'])
def analyze():
    if model is None:
        return jsonify({'error': 'Model not loaded'}), 500

    try:
        # Get the uploaded image from the request
        image_file = request.files['image']

        # Open the image using Pillow (PIL)
        img = Image.open(image_file.stream)

        # Preprocess the image
        processed_image = preprocess_image(img)

        if processed_image is None:
            return jsonify({'error': 'Image preprocessing failed'}), 400

        # Make a prediction using the model
        prediction = model.predict(processed_image)

        # Interpret the prediction based on your model's output
        # Assuming binary classification: 0 = Healthy, 1 = Unhealthy
        is_healthy = prediction[0][0] < 0.5  # Adjust threshold if necessary

        confidence_score = float(prediction[0][0])  # Confidence score for unhealthy class

        print(f"Prediction: {'Healthy' if is_healthy else 'Unhealthy'}, Confidence: {confidence_score}")

        return jsonify({
            'healthy': is_healthy,
            'confidence': confidence_score
        })

    except Exception as e:
        print(f"Error during analysis: {e}")
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)
