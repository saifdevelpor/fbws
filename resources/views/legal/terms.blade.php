<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms & Conditions</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Top Bar -->
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">Welfare Society</a>
            <div class="ms-auto d-flex gap-2">
                <a class="btn btn-primary btn-sm" href="{{ route('terms.conditions') }}">Terms</a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('privacy.policy') }}">Privacy</a>
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
                                <div class="rounded-3 bg-success-subtle text-success fw-bold d-flex align-items-center justify-content-center"
                                    style="width:48px;height:48px;">TC</div>
                                <div>
                                    <h1 class="h3 fw-bold mb-1">Terms & Conditions</h1>
                                    <p class="text-muted mb-0">Last Updated: <span
                                            class="fw-semibold">{{ date('F d, Y') }}</span></p>
                                </div>
                            </div>

                            <div class="alert alert-warning border-0">
                                This website is for registered society members. By using it, you agree to the terms
                                below.
                            </div>

                            <h2 class="h5 fw-bold mt-4">1) Membership Use</h2>
                            <p class="text-muted">
                                This platform is only for society members. Members must provide correct information and
                                keep it updated.
                            </p>

                            <h2 class="h5 fw-bold mt-4">2) Monthly Contributions</h2>
                            <p class="text-muted">
                                Members agree to pay monthly contributions on time as decided by the society. Late
                                payments may affect borrowing eligibility.
                            </p>

                            <h2 class="h5 fw-bold mt-4">3) Borrowing Society Items</h2>
                            <p class="text-muted mb-2">Items can be borrowed under these rules:</p>
                            <ul class="text-muted">
                                <li>Admin approval is required before borrowing</li>
                                <li>Items must be returned on time</li>
                                <li>Damaged/lost items must be compensated</li>
                            </ul>

                            <h2 class="h5 fw-bold mt-4">4) Account Responsibility</h2>
                            <p class="text-muted">
                                You are responsible for keeping your login details secure. Account sharing is not
                                allowed.
                            </p>

                            <h2 class="h5 fw-bold mt-4">5) Misuse</h2>
                            <p class="text-muted mb-2">You must not:</p>
                            <ul class="text-muted">
                                <li>Use fake information</li>
                                <li>Attempt hacking, data scraping, or unauthorized access</li>
                                <li>Misuse member or society data</li>
                            </ul>
                            <div class="border rounded-3 p-3 bg-light">
                                <p class="mb-0">
                                    Admins may suspend accounts in case of misuse or violation.
                                </p>
                            </div>

                            <h2 class="h5 fw-bold mt-4">6) Changes to Terms</h2>
                            <p class="text-muted">
                                Admins may update these terms when needed. Members will be informed through society
                                channels.
                            </p>

                            <h2 class="h5 fw-bold mt-4">7) Limitation of Liability</h2>
                            <p class="text-muted">
                                The society is not responsible for personal losses unrelated to society operations.
                                Records are maintained with care,
                                but technical issues may occasionally occur.
                            </p>

                            <h2 class="h5 fw-bold mt-4">8) Contact</h2>
                            <div class="border rounded-3 p-3">
                                <div class="fw-semibold">Society Admin</div>
                                <div class="text-muted">Phone: <span class="fw-semibold">+923012704423</span></div>
                                <div class="text-muted">Email: <span class="fw-semibold">farookabrotherswelfearsociety@gmail.com</span></div>
                            </div>

                            <hr class="my-4">

                            <p class="small text-muted mb-0">
                                By using this website, you accept these Terms & Conditions.
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
