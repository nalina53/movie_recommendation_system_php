<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".../css/slider.css">
    <title>Movie Slider</title>
    <style>
        /*ss */

body {
    font-family: Arial, sans-serif;
}

.slider {
    position: relative;
    max-width: 600px;
    margin: auto;
    overflow: hidden;
}

.slides {
    display: flex;
    transition: transform 0.5s ease;
}

.slide {
    min-width: 100%;
    height: 500px;
    box-sizing: border-box;
    text-align: center;
}

.slide img {
            width: 100%;
            height: 100%; /* Set to 100% to fill the slide */
            object-fit: contain; /* Keeps the entire image visible */
        }

button {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(255, 255, 255, 0.7);
    border: none;
    padding: 10px;
    cursor: pointer;
}

.prev {
    left: 10px;
}

.next {
    right: 10px;
}

    </style>
</head>
<body>
    <div class="slider">
        <div class="slides">
            <div class="slide active">
                <img src="../images/D.jpg" alt="Movie 1">
                <h2>Movie Title 1</h2>
            </div>
            <div class="slide">
                <img src="../images/INC.jpg" alt="Movie 2">
                <h2>Movie Title 2</h2>
            </div>
            <div class="slide">
                <img src="../images/IO.jpg" alt="Movie 3">
                <h2>Movie Title 3</h2>
            </div>
            <!-- Add more slides as needed -->
        </div>
        <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
        <button class="next" onclick="moveSlide(1)">&#10095;</button>
    </div>

    <script >

let currentSlide = 0;

function showSlide(index) {
    const slides = document.querySelectorAll('.slide');
    if (index >= slides.length) {
        currentSlide = 0;
    } else if (index < 0) {
        currentSlide = slides.length - 1;
    } else {
        currentSlide = index;
    }
    const offset = -currentSlide * 100;
    document.querySelector('.slides').style.transform = `translateX(${offset}%)`;
}

function moveSlide(direction) {
    showSlide(currentSlide + direction);
}

// Optionally, auto slide every 3 seconds
setInterval(() => moveSlide(1), 3000);

    </script>
</body>
</html>
