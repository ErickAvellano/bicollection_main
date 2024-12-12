@extends('Components.layout')

@section('styles')
    <style>
        body {
            font-family: "Futura PT", sans-serif;
            background: #fafafa;
            margin: 0;
            padding: 0;
            overflow: auto;
            height: 100%;
            margin: 0; /* Ensure no default margin */

        }

        header {
            background-color: #228b22;
            padding: 10px 70px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #logo {
            max-height: 60px;
        }

        .search-bar {
            display: flex;
            gap: 10px;
        }

        .search-bar input {
            border-radius: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            flex-grow: 1;
        }

        .search-bar button {
            background-color: #ffd700;
            border: none;
            border-radius: 20px;
            padding: 10px;
        }

        .search-bar button img {
            width: 20px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
        }

        .box {
            border: 2px solid #228b22;
            padding: 20px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            flex: 1;
            margin: 10px;
            min-width: 300px;
        }

        .product-preview img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            color: #228b22;
        }

        select,
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 2px solid #228b22;
        }

        .color-options {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .color-options div {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid #228b22;
            cursor: pointer;
        }

        .pattern-options {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .pattern-options button {
            padding: 10px;
            border-radius: 5px;
            border: 2px solid #228b22;
            background-color: #d0e8d0;
            cursor: pointer;
            flex: 1;
            margin-bottom: 15px;
        }


        .pattern-options button.selected {
        border: 2px solid #228b22;
        background-color: #a0c8a0;
        }

        button.remove, button.save {
            width: 100%;
            padding: 10px;
            background-color: #228b22;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button.remove:hover, button.save:hover {
            background-color: #1e7e1e;
        }

        /* Container for buttons (optional if needed) */
        .button {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                padding: 10px;
            }

            .box {
                flex-basis: 100%;
                margin-bottom: 20px;
            }

            .pattern-options button {
                margin-bottom: 10px;
            }
        }
    </style>
@endsection
@section('content')
<div class="container">
    <!-- Product Preview -->
    <div class="box product-preview">
        <h2>Product Preview</h2>
        <!-- Pot Shape in SVG -->
        <svg viewBox="0 0 120 150" xmlns="http://www.w3.org/TR/SVG2/">
            <!-- Pot rim -->
            <path id="pot-rim" d="M19.5,20 h80 a15,15 0 0 1 10,10 v7 a15,15 0 0 1 -10,10 h-80 a15,15 0 0 1 -10,-10 v-7 a15,15 0 0 1 10,-10" fill="#D97E47" />

            <!-- Pot body -->
            <path id="pot-body" d="M20,47 h80 l-10,85 h-60 l-10,-85 z" fill="#D97E47" />

            <!-- Pattern Overlay (Now Above the Body and Matching its Shape) -->
            <g id="pattern-overlay" opacity="0.8">
                <path id="pattern-path" d="M20,47 h80 l-10,85 h-60 l-10,-85 z" fill="url(#dot-pattern)" />
            </g>

            <!-- Pot base -->
            <path id="pot-base" d="M45,125 h50l-10,13 h-50l-10,-13 z" fill="#D97E47" />

            <!-- Pattern Definitions -->
            <defs>
                <pattern id="dot-pattern" width="10" height="10" patternUnits="userSpaceOnUse">
                    <circle cx="5" cy="5" r="3" fill="#e3bc9a" />
                </pattern>
                <pattern id="stripe-pattern" width="10" height="10" patternUnits="userSpaceOnUse">
                    <rect width="5" height="10" fill="#e3bc9a" />
                </pattern>
                <pattern id="flower-pattern" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="8" fill="yellow" />
                    <circle cx="7" cy="10" r="5" fill="#e3bc9a" />
                    <circle cx="13" cy="10" r="5" fill="#e3bc9a" />
                    <circle cx="10" cy="7" r="5" fill="#e3bc9a" />
                    <circle cx="10" cy="13" r="5" fill="#e3bc9a" />
                </pattern>
            </defs>
        </svg>

    </div>

    <!-- Product Customization -->
    <div class="box customization">
        <h2>Product Customization</h2>
        <label for="pot-type">Pot Type</label>
        <select class="material-select">
            <option value="" disabled selected>---Select Pot Type---</option>
            <option value="ceramic">Ceramic</option>
            <option value="plastic">Plastic</option>
            <option value="clay">Clay</option>
        </select>

        <label for="material">Select Material</label>
        <select class="material-select">
            <option value="" disabled selected>---Select Material---</option>
            <option value="ceramic">Ceramic</option>
            <option value="plastic">Plastic</option>
            <option value="clay">Clay</option>
        </select>

        <label>Rim</label>
        <div class="color-options" id="rim-options">
            <div style="background-color: red;" data-color="red"></div>
            <div style="background-color: green;" data-color="green"></div>
            <div style="background-color: brown;" data-color="brown"></div>
            <div style="background-color: black;" data-color="black"></div>
            <div style="background-color: pink;" data-color="pink"></div>
        </div>
        
        <label>Body</label>
        <div class="color-options" id="body-options">
            <div style="background-color: red;" data-color="red"></div>
            <div style="background-color: green;" data-color="green"></div>
            <div style="background-color: brown;" data-color="brown"></div>
            <div style="background-color: black;" data-color="black"></div>
            <div style="background-color: pink;" data-color="pink"></div>
        </div>

        <label>Base</label>
        <div class="color-options" id="base-options">
            <div style="background-color: red;" data-color="red"></div>
            <div style="background-color: green;" data-color="green"></div>
            <div style="background-color: brown;" data-color="brown"></div>
            <div style="background-color: black;" data-color="black"></div>
            <div style="background-color: pink;" data-color="pink"></div>
        </div>

        <label>Pattern</label>
        <div class="pattern-options">
            <button>Dot</button>
            <button>Stripe</button>
            <button>Flower</button>
        </div>

        <div class="button">
            <button class="remove">Remove Pattern</button>
            <button class="save">Save</button>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    // Get the SVG elements
    const potRim = document.getElementById('pot-rim');
    const potBody = document.getElementById('pot-body');
    const potBase = document.getElementById('pot-base');

    // Function to add color selection functionality
    function addColorSelection(optionGroupId, targetElement) {
        const options = document.getElementById(optionGroupId).children;
        for (let option of options) {
            option.addEventListener('click', function () {
                const selectedColor = this.getAttribute('data-color');
                targetElement.setAttribute('fill', selectedColor);
            });
        }
    }

    // Add color selection for Rim, Body, and Base
    addColorSelection('rim-options', potRim);
    addColorSelection('body-options', potBody);
    addColorSelection('base-options', potBase);

    // Pattern options
    const patternButtons = document.querySelectorAll('.pattern-options button');
    patternButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Show the pattern overlay again (in case it was hidden)
            patternOverlay.style.display = 'block';
            
            // Remove the selected class from all buttons
            patternButtons.forEach(btn => btn.classList.remove('selected'));
            
            // Add the selected class to the clicked button
            this.classList.add('selected');
            
            // Get the pattern name from the button's text content (Dot, Stripe, Flower)
            const pattern = this.textContent.toLowerCase();
            
            // Apply the selected pattern to the overlay path
            patternPath.setAttribute('fill', `url(#${pattern}-pattern)`);
        });
    });

    // Get all Remove Pattern buttons (since it's now a class, it returns a NodeList)
    const removePatternBtns = document.querySelectorAll('.remove');

    const patternOverlay = document.getElementById('pattern-overlay');
    const patternPath = document.getElementById('pattern-path');

    // Loop through each Remove Pattern button and add the event listener
    removePatternBtns.forEach(removePatternBtn => {
        removePatternBtn.addEventListener('click', function() {
            // Hide the pattern overlay by setting its display property to 'none'
            patternOverlay.style.display = 'none';
            
            // Reset the pattern path (remove the applied pattern)
            patternPath.setAttribute('fill', 'none');
        });
    });

    // Save button
    document.querySelector('.save').addEventListener('click', function() {
        alert('Saved!');
    });

</script>
@endsection
