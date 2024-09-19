<script src="{{ asset('backend/assets/js/layout.js') }}"></script>
<!-- Icons CSS -->
<link rel="stylesheet" href="{{ asset('backend/assets/css/tailwind2.css') }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>

{{-- TOASTR CDN --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

    #btnSuccess {
        background-color: #1bc5bd;
        border-color: #1bc5bd;
    }

    #btnInfo {
        background-color: #187de4;
        border-color: #187de4;
    }

    #btnWarning {
        background-color: #ee9d01;
        border-color: #ee9d01;
    }

    #btnError {
        background-color: #ee2d41;
        border-color: #ee2d41;
    }

    #btnSuccess,
    #btnInfo,
    #btnWarning,
    #btnError {
        color: #fff;
        border-radius: 0.5rem;
        font-weight: 400 !important;
        font-size: 0.765rem;
        width: 90px;
        height: 36px;
        margin: 3px;
        cursor: pointer;
    }

    #toast-container {
        margin-top: 20px;
    }

    .toast-success,
    .toast-info,
    .toast-warning,
    .toast-error {
        position: relative;
        width: 300px !important;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        border-radius: 10px !important;
        background-color: #2c3e50;
        color: #ecf0f1 !important;
        border: 1px solid #34495e;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        padding-right: 55px;
        padding-left: 60px;
    }

    button.toast-close-button {
        color: #fff;
        font-size: 25px;
        padding: 0;
        cursor: pointer;
        background: transparent;
        border: none;
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        line-height: 20px;
        text-align: center;
    }

    /* Icon Customization */
    #toast-container>.toast-success {
        background-image: url('https://img.icons8.com/fluency/48/000000/checked.png') !important;
        background-repeat: no-repeat;
        background-position: 15px center;
        background-size: 24px 24px;
    }

    #toast-container>.toast-info {
        background-image: url('https://img.icons8.com/fluency/48/000000/info.png') !important;
        background-repeat: no-repeat;
        background-position: 15px center;
        background-size: 24px 24px;
    }

    #toast-container>.toast-warning {
        background-image: url('https://img.icons8.com/fluency/48/000000/warning-shield.png') !important;
        background-repeat: no-repeat;
        background-position: 15px center;
        background-size: 24px 24px;
    }

    #toast-container>.toast-error {
        background-image: url('https://img.icons8.com/fluency/48/000000/cancel.png') !important;
        background-repeat: no-repeat;
        background-position: 15px center;
        background-size: 24px 24px;
    }

    /* Close Button Styles */
    .toast-close-button {
        color: #fff;
        font-size: 20px;
        padding: 5px;
        cursor: pointer;
        background: transparent;
        border: none;
    }

    .toast-message {
        margin-right: 5px;
    }

    /* Reference Links */
    .references {
        margin-top: 2rem;
        font-size: 0.75rem;
        display: flex;
        justify-content: flex-start;
        flex-direction: column;
        align-items: center;
    }

    .references a {
        text-decoration: none;
        text-align: left;
    }
</style>
@stack('styles')
