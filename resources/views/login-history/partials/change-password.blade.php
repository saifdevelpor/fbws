@php
$pwUser = auth()->user();
$pwAvatar = $pwUser && $pwUser->profile_photo
? asset($pwUser->profile_photo)
: asset('assets/img/avatars/5.png');
@endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');

body {
    font-family: Inter, sans-serif;
}

.password-card {
    border-radius: 20px;
    overflow: hidden;
}

.password-header {
    background: linear-gradient(135deg, #F7721E 0%, #e85f0c 100%);
}

.password-strength-box {
    border: 1px solid #e9edf2;
    border-radius: 16px;
    padding: 16px;
    margin-top: 12px;
    background: #fafbfc;
}

.password-bar {
    height: 8px;
    background: #e9edf2;
    border-radius: 999px;
    overflow: hidden;
    margin-bottom: 10px;
}

.password-bar-fill {
    height: 100%;
    width: 0%;
    transition: 0.3s;
    background: #dc3545;
}

.password-rules {
    list-style: none;
    padding: 0;
    margin: 0;
}

.password-rules li {
    font-size: 13px;
    padding: 6px 0;
    color: #6b7280;
}

.password-rules li.valid {
    color: #198754;
    font-weight: 600;
}

.password-match {
    font-size: 13px;
    margin-top: 10px;
    display: none;
}

.password-match.ok {
    color: #198754;
    display: block;
}

.password-match.bad {
    color: #dc3545;
    display: block;
}

.btn-update {
    background: #F7721E;
    border: none;
}

.btn-update:hover {
    background: #e56414;
}
</style>

<div class="container mt-4">

    <div class="card border-0 shadow-sm password-card">

        <div class="card-body p-0">

            <div class="p-4 text-white password-header">

                <div class="d-flex align-items-center gap-3">

                    <div class="rounded-circle overflow-hidden border border-2 border-white"
                        style="width:50px;height:50px;">
                        <img src="{{ $pwAvatar }}" style="width:100%;height:100%;object-fit:cover;">
                    </div>

                    <div>
                        <h5 class="mb-1 fw-bold" style="color: white">Change password</h5>
                        <small>You can update password for: <strong>{{ $pwUser->name }}</strong></small>
                    </div>

                </div>

            </div>

            <div class="p-4">

                <form method="POST" action="{{ route('password.change') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-12">

                            <label class="form-label fw-semibold">Current password</label>

                            <div class="input-group">

                                <input type="password" name="current_password" id="current_password"
                                    class="form-control" required>

                                <button class="btn btn-outline-secondary" type="button" data-toggle="#current_password">
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">New password</label>

                            <div class="input-group">

                                <input type="password" name="password" id="password" class="form-control" required>

                                <button class="btn btn-outline-secondary" type="button" data-toggle="#password">
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>

                            <div class="password-strength-box">

                                <div class="password-bar">
                                    <div id="strengthBar" class="password-bar-fill"></div>
                                </div>

                                <ul class="password-rules">

                                    <li id="rule-length">At least 8 characters</li>

                                    <li id="rule-upper">One uppercase letter</li>

                                    <li id="rule-lower">One lowercase letter</li>

                                    <li id="rule-number">One number</li>

                                    <li id="rule-symbol">One special character</li>

                                </ul>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">Confirm password</label>

                            <div class="input-group">

                                <input type="password" name="password_confirmation" id="confirmPassword"
                                    class="form-control" required>

                                <button class="btn btn-outline-secondary" type="button" data-toggle="#confirmPassword">
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>

                            <div id="passwordMatch" class="password-match">
                                Passwords do not match
                            </div>

                        </div>

                        <div class="col-12">

                            <button type="submit" class="btn btn-update text-white px-4">

                                Update password

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll("[data-toggle]").forEach(btn => {
    btn.addEventListener("click", () => {
        let input = document.querySelector(btn.dataset.toggle);
        input.type = input.type === "password" ? "text" : "password";
    });
});

let password = document.getElementById("password");
let confirmPassword = document.getElementById("confirmPassword");

let bar = document.getElementById("strengthBar");

function checkRule(id, condition) {
    let el = document.getElementById(id);
    if (condition) {
        el.classList.add("valid");
    } else {
        el.classList.remove("valid");
    }
}

password.addEventListener("input", () => {

    let val = password.value;

    let length = val.length >= 8;
    let upper = /[A-Z]/.test(val);
    let lower = /[a-z]/.test(val);
    let number = /[0-9]/.test(val);
    let symbol = /[^A-Za-z0-9]/.test(val);

    checkRule("rule-length", length);
    checkRule("rule-upper", upper);
    checkRule("rule-lower", lower);
    checkRule("rule-number", number);
    checkRule("rule-symbol", symbol);

    let score = [length, upper, lower, number, symbol].filter(Boolean).length;

    bar.style.width = (score * 20) + "%";

    if (score <= 2) {
        bar.style.background = "#dc3545";
    } else if (score <= 4) {
        bar.style.background = "#f59e0b";
    } else {
        bar.style.background = "#198754";
    }

});

confirmPassword.addEventListener("input", () => {

    let msg = document.getElementById("passwordMatch");

    if (confirmPassword.value === "") {
        msg.style.display = "none";
        return;
    }

    if (confirmPassword.value === password.value) {
        msg.className = "password-match ok";
        msg.innerText = "Passwords match";
    } else {
        msg.className = "password-match bad";
        msg.innerText = "Passwords do not match";
    }

});
</script>

@if(session('password_changed'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: 'Password updated successfully',
    confirmButtonColor: '#F7721E'
})
</script>
@endif

@if($errors->any())
<script>
Swal.fire({
    icon: 'error',
    title: 'Update Failed',
    html: `@foreach($errors->all() as $error) {{ $error }} <br> @endforeach`,
    confirmButtonColor: '#F7721E'
})
</script>
@endif