<?php $this->title = 'MediBook — Online Doctor Appointments'; ?>

<section class="hero-cta mb-5 card-hover">
    <div class="row align-items-center">
        <div class="col-md-6">
            <p class="text-uppercase text-primary-dark fw-semibold">Find care in minutes</p>
            <h1 class="display-5 fw-bold mb-3">Book appointments with trusted doctors near you</h1>
            <p class="lead">Browse specialties, see office profiles, and reserve 30-minute slots with a single click.</p>
            <a href="<?= BASE_URL ?>/patient/specialties" class="btn btn-primary-custom">Explore specialties</a>
        </div>
        <div class="col-md-6 text-center">
            <div class="border rounded-3 p-5" style="background:#fff;">
                <p class="fw-bold">Find an office by name or location</p>
                <div class="w-75 mx-auto" style="height:160px; background:#e9f2ff;"></div>
            </div>
        </div>
    </div>
</section>

<section class="section-card mb-5">
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <h2 class="h4 mb-1">Quick search</h2>
            <p class="text-muted">Type an office name, city, or specialty.</p>
        </div>
        <div class="col-md-8">
            <form class="row g-2" method="get" action="<?= BASE_URL ?>/patient/offices/search">
                <div class="col-md-6">
                    <input type="search" name="q" class="form-control" placeholder="Search by office or location" autocomplete="off">
                </div>
                <div class="col-md-4">
                    <select name="specialty" class="form-select">
                        <option value="">All specialties</option>
                        <?php foreach ($specialties ?? [] as $specialty): ?>
                            <option value="<?= $specialty['id'] ?>"><?= htmlspecialchars($specialty['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary-custom w-100">Search</button>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <p class="text-uppercase text-primary-dark mb-1">Trending</p>
            <h2 class="h4 mb-0">Popular specialties</h2>
        </div>
        <a href="<?= BASE_URL ?>/patient/specialties" class="text-decoration-none text-primary-dark">View all</a>
    </div>
    <div class="row g-3">
        <?php if (!empty($specialties)): ?>
            <?php foreach (array_slice($specialties, 0, 6) as $specialty): ?>
                <div class="col-sm-6 col-md-4">
                    <div class="card h-100 card-hover border-0 rounded-4">
                        <div class="card-body">
                            <p class="text-uppercase text-primary-dark small mb-1">Specialty</p>
                            <h3 class="h6 fw-bold mb-2"><?= htmlspecialchars($specialty['name']) ?></h3>
                            <p class="small mb-3"><?= htmlspecialchars($specialty['description'] ?? 'Explore curated offices offering this specialty.') ?></p>
                            <a href="<?= BASE_URL ?>/patient/offices/specialty/<?= $specialty['id'] ?>" class="btn btn-outline-primary w-100">View offices</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-custom">No specialties are listed yet.</div>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <p class="text-uppercase text-primary-dark mb-1">Featured</p>
            <h2 class="h4 mb-0">Offices near you</h2>
        </div>
        <a href="<?= BASE_URL ?>/patient/offices/search" class="text-decoration-none text-primary-dark">Browse more</a>
    </div>
    <div class="row g-3">
        <?php if (!empty($offices)): ?>
            <?php foreach (array_slice($offices, 0, 6) as $office): ?>
                <div class="col-sm-6 col-md-4">
                    <div class="card h-100 card-hover border-0 rounded-4">
                        <div class="card-body">
                            <h3 class="h6 fw-bold mb-1"><?= htmlspecialchars($office['office_name']) ?></h3>
                            <p class="small text-muted mb-1"><?= htmlspecialchars($office['address'] ?? 'Address not provided') ?></p>
                            <p class="small mb-3"><?= htmlspecialchars($office['description'] ?? 'See profile for details.') ?></p>
                            <a href="<?= BASE_URL ?>/patient/offices/show/<?= $office['id'] ?>" class="btn btn-outline-primary w-100">View profile</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-custom">No offices are featured right now.</div>
            </div>
        <?php endif; ?>
    </div>
</section>
