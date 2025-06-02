@extends('layouts.app')

@section('style')
    <style>
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #007bff;
        }

        .card {
            max-width: 500px;
            margin: auto;
            margin-top: 50px;
        }

        .image_box {
            width: fit-content;
            margin: auto;
            position: relative;
        }

        .image_box svg {
            position: absolute;
            right: 0px;
            bottom: 44px;
            fill: white;
            background: gray;
            cursor: pointer;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            /* White overlay with opacity */
            display: none;
            /* Default hidden, JS ke bina static hai */
            justify-content: center;
            align-items: center;
            border-radius: 50%;
        }

        .spinner-border {
            width: 2rem;
            height: 2rem;
        }
    </style>
@stop

@section('content')
    <div class="container">
        @include('modals.change_password_modal')
        <div class="card shadow">
            <div class="card-body text-center">
                <div class="image_box position-relative">
                    <input hidden type="file" accept="image/*" name="user_profile__pic" id="user_profile__pic">
                    <img src="{{ getUserProfilePic(auth()->user()) }}" alt="Profile Picture" class="profile-img mb-3"
                        id="profile-img">
                    <!-- White overlay with loader -->
                    <div class="overlay" id="image-overlay">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                    <label for="user_profile__pic">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                    </label>
                </div>
                <h4 class="card-title">{{ auth()->user()?->name }}</h4>
                <p class="text-muted mb-1">{{ auth()->user()?->email }}</p>
                <p class="text-muted mb-1">
                    <button class="btn btn-sm btn-primary change_pass_btn">Change password</button>
                </p>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $("body").on('click', '.change_pass_btn', function() {
                $("#changePasswordModal").modal('show')
            })

            $("#changePasswordForm").on('submit', function(e) {
                e.preventDefault();
                let url = @json(route('change.password'));
                let formData = new FormData($('#changePasswordForm')[0])
                let btn = $(this).find('button[type="submit"]')
                $(".errors_list").addClass('d-none')
                btn.text('Loading...').prop('disabled', true)

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (responseCode?.responseCode === 200) {
                            btn.text('Change Password').prop('disabled', false)
                            $("#changePasswordModal").modal('hide')
                            $('#changePasswordForm')[0].reset()
                        }
                    },
                    error: function(error) {
                        btn.text('Change Password').prop('disabled', false)
                        console.log(error)
                        let errors = JSON.parse(error?.responseText)
                        if (errors?.responseCode == 401) {
                            $(".errors_list").removeClass('d-none')
                            $(".errors_list ul").html('').append(`<li>${errors.message}</li>`)
                        }
                        if (error?.responseText && errors?.errors)
                            for (let [key, error] of Object.entries(errors.errors)) {
                                $(".errors_list").removeClass('d-none')
                                $(".errors_list ul").html('').append(`<li>${error[0]}</li>`)
                            }
                    }
                })
            })

            $("#user_profile__pic").on('change', function(e) {
                let _this = $(this)
                let file = e.target?.files[0]
                $(".profile-img").attr("src", URL.createObjectURL(file))
                $("#image-overlay").css('display', 'flex')
                updateProfilePic(file)

            })

            function updateProfilePic(file) {
                let url = @json(route('update.profile.pic'));
                let formData = new FormData();
                formData.append('file', file)
                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(response) {
                        if(response.responseCode == 200) {
                            $("#image-overlay").css('display', 'none')
                        }
                    },
                    error: function(error) {
                        $("#image-overlay").css('display', 'none')
                        console.log(error)
                    }
                })
            }

        })
    </script>
@stop
