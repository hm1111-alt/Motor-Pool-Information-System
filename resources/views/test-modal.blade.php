<!DOCTYPE html>
<html>
<head>
    <title>Test Itinerary Modal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Test Itinerary Modal</h1>
        <button type="button" onclick="openCreateItineraryModal()" class="btn btn-primary">Open Modal</button>
    </div>

    @include('itineraries.modals.create-itinerary-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>