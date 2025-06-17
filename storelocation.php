<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branchs Location - ATMABISWAS </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        .header {
            text-align: center;
            padding: 20px;
            background-color: #4CAF50;
            color: white;
        }
        .container {
            display: flex;
            flex-grow: 1;
        }
        .store-list {
            width: 30%;
            overflow-y: auto;
            background-color: white;
            border-right: 1px solid #ddd;
            padding: 20px;
        }
        .store-item {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .store-item h3 {
            margin: 0;
            font-size: 18px;
        }
        .store-item p {
            margin: 5px 0;
            color: #555;
            font-size: 14px;
        }
        .store-item button {
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .store-item button:hover {
            background-color: #45a049;
        }
        #map {
            width: 70%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Store Locator</h1>
    </div>
    <div class="container">
        <div class="store-list" id="store-list">
            <!-- Store items will be dynamically added here -->
        </div>
        <div id="map"></div>
    </div>

    <script>
        const stores = [
            { name: "Gadget & Gear - Uttara, North Tower", location: { lat: 23.8751, lng: 90.3854 }, address: "Shop 506, 5th Floor, North Tower, Uttara, Dhaka 1230", phone: "0967-8666709", hours: "10:00 AM - 9:00 PM", distance: "144.93 km" },
            { name: "Gadget & Gear - Banani, Road 11", location: { lat: 23.7934, lng: 90.4044 }, address: "ANZ Huq Eleven Square, Plot 01, Block H, Banani, Dhaka 1213", phone: "0967-8666785", hours: "10:00 AM - 9:00 PM", distance: "138.08 km" },
            { name: "Gadget & Gear - Level 6, Bashundhara City", location: { lat: 23.7491, lng: 90.3768 }, address: "Shop 75-76, Block D, Level 6, Bashundhara City", phone: "01717-151515", hours: "10:00 AM - 9:00 PM", distance: "125.50 km" }
        ];

        function initMap() {
            const map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 23.8103, lng: 90.4125 }, // Centered on Dhaka
                zoom: 12,
            });

            stores.forEach((store, index) => {
                const marker = new google.maps.Marker({
                    position: store.location,
                    map,
                    title: store.name,
                });

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div>
                            <h3>${store.name}</h3>
                            <p><strong>Address:</strong> ${store.address}</p>
                            <p><strong>Phone:</strong> ${store.phone}</p>
                            <p><strong>Hours:</strong> ${store.hours}</p>
                            <button onclick="alert('Navigating to ${store.name}')">Get Directions</button>
                        </div>
                    `,
                });

                marker.addListener("click", () => {
                    infoWindow.open(map, marker);
                });

                // Add to store list
                const storeList = document.getElementById("store-list");
                const storeItem = document.createElement("div");
                storeItem.className = "store-item";
                storeItem.innerHTML = `
                    <h3>${store.name}</h3>
                    <p><strong>Address:</strong> ${store.address}</p>
                    <p><strong>Phone:</strong> ${store.phone}</p>
                    <p><strong>Hours:</strong> ${store.hours}</p>
                    <p><strong>Distance:</strong> ${store.distance}</p>
                    <button onclick="alert('Navigating to ${store.name}')">Get Directions</button>
                `;
                storeList.appendChild(storeItem);
            });
        }
    </script>
    <script async defer src="https://maps.gomaps.pro/maps/api/js?key=AlzaSy0QJPP-rfRIayVVb2TT8I1Zc8XOqYFcN9h&callback=initMap"></script>
</body>
</html>
