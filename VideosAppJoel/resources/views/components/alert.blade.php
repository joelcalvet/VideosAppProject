@props(['type' => 'success', 'message' => ''])

<div class="alert alert-{{ $type }}">
    {{ $message }}
</div>

<style>
    .alert {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px;
        border-radius: 5px;
        color: white;
        z-index: 1000;
        animation: fadeInOut 5s forwards;
    }

    .alert-success {
        background-color: #4CAF50; /* Verd */
    }

    .alert-error {
        background-color: #F44336; /* Vermell */
    }

    @keyframes fadeInOut {
        0% { opacity: 0; transform: translateY(-20px); }
        10% { opacity: 1; transform: translateY(0); }
        90% { opacity: 1; transform: translateY(0); }
        100% { opacity: 0; transform: translateY(-20px); }
    }
</style>
