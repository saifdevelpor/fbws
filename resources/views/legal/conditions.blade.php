<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Conditions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">Welfare Society</a>
            <div class="ms-auto d-flex gap-2">
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('terms.page') }}">Terms</a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('privacy.policy') }}">Privacy</a>
                <a class="btn btn-primary btn-sm" href="{{ route('conditions.page') }}">Conditions</a>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-xl-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 p-md-5">
                            <h1 class="h3 fw-bold mb-3">Conditions</h1>
                            <p class="text-muted">
                                These conditions define how members should use the welfare website and society services.
                            </p>

                            <h2 class="h5 fw-bold mt-4">1) Service Conditions</h2>
                            <ul class="text-muted">
                                <li>All entries must be genuine and verifiable.</li>
                                <li>Orders, deliveries, and payments must follow admin policy.</li>
                                <li>Members should avoid abusive or misleading requests.</li>
                            </ul>

                            <h2 class="h5 fw-bold mt-4">2) Account Conditions</h2>
                            <ul class="text-muted">
                                <li>One account per member only.</li>
                                <li>Members are responsible for account security.</li>
                                <li>Admin can suspend misuse accounts.</li>
                            </ul>

                            <h2 class="h5 fw-bold mt-4">3) Operational Conditions</h2>
                            <p class="text-muted">
                                Timings, item availability, and order approval depend on society operations and may vary
                                without prior notice.
                            </p>

                            <h2 class="h5 fw-bold mt-4">4) Legal Conditions</h2>
                            <p class="text-muted mb-0">
                                By using this website, you accept all conditions and society rules.
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
</body>

</html>
