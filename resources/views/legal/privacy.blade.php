<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Top Bar -->
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">Welfare Society</a>
            <div class="ms-auto d-flex gap-2">
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('terms.conditions') }}">Terms</a>
                <a class="btn btn-primary btn-sm" href="{{ route('privacy.policy') }}">Privacy</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-xl-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 p-md-5">

                            <div class="d-flex align-items-start gap-3 mb-4">
                                <div class="rounded-3 bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center"
                                    style="width:48px;height:48px;">PP</div>
                                <div>
                                    <h1 class="h3 fw-bold mb-1">Privacy Policy</h1>
                                    <p class="text-muted mb-0">Last Updated: <span
                                            class="fw-semibold">{{ date('F d, Y') }}</span></p>
                                </div>
                            </div>

                            <div class="alert alert-info border-0">
                                We respect your privacy. This policy explains how we collect, use, and protect member
                                information.
                            </div>

                            <h2 class="h5 fw-bold mt-4">1) Information We Collect</h2>
                            <ul class="text-muted">
                                <li>Name, phone number</li>
                                <li>Address (optional)</li>
                                <li>Monthly contribution records</li>
                                <li>Borrowed items history (plates, jugs, glasses, etc.)</li>
                            </ul>

                            <h2 class="h5 fw-bold mt-4">2) How We Use Your Information</h2>
                            <p class="text-muted">
                                We use this data only to manage society activities: member management, contributions
                                tracking,
                                item borrowing/return tracking, and important updates.
                            </p>
                            <div class="border rounded-3 p-3 bg-light">
                                <p class="mb-0">
                                    <span class="fw-semibold">We do not sell</span> or share your personal data with
                                    third parties.
                                </p>
                            </div>

                            <h2 class="h5 fw-bold mt-4">3) Data Protection</h2>
                            <ul class="text-muted">
                                <li>Secure login and restricted access for admins</li>
                                <li>Basic security controls and activity monitoring</li>
                                <li>Regular backups to reduce data loss risk</li>
                            </ul>

                            <h2 class="h5 fw-bold mt-4">4) Cookies</h2>
                            <p class="text-muted">
                                This website may use essential cookies for login/session functionality. We don’t store
                                sensitive data in cookies.
                            </p>

                            <h2 class="h5 fw-bold mt-4">5) Member Rights</h2>
                            <p class="text-muted mb-2">Members can request:</p>
                            <ul class="text-muted">
                                <li>Correction of incorrect information</li>
                                <li>Deletion of data after leaving the society (where applicable)</li>
                            </ul>

                            <h2 class="h5 fw-bold mt-4">6) Data Retention</h2>
                            <p class="text-muted">
                                We keep records only as long as needed for society management and accountability.
                            </p>

                            <h2 class="h5 fw-bold mt-4">7) Contact</h2>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3">
                                        <div class="fw-semibold">Society Admin</div>
                                        <div class="text-muted">Phone: <span class="fw-semibold">+923012704423</span>
                                        </div>
                                        <div class="text-muted">Email: <span class="fw-semibold">farookabrotherswelfearsociety@gmail.com</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3">
                                        <div class="fw-semibold">Quick Links</div>
                                        <div class="text-muted">
                                            <a href="{{ route('terms.conditions') }}">Terms & Conditions</a> •
                                            <a href="{{ url('/') }}">Home</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <p class="small text-muted mb-0">
                                By using this website, you agree to this Privacy Policy.
                            </p>
                        </div>
                    </div>

                    <div class="text-center text-muted small mt-3">
                        © {{ date('Y') }} Welfare Society. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
