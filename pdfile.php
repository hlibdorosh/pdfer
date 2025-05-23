
<?php

// === Language loader ===
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'en';
$_SESSION['lang'] = $lang;
$lang_file = __DIR__ . "/lang/$lang.php";
$t = file_exists($lang_file) ? require $lang_file : require __DIR__ . "/lang/en.php";
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>PDF Tools</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .tool-card {
            transition: 0.3s ease;
        }
        .tool-card:hover {
            transform: scale(1.05);
            background-color: #e9ecef;
        }
        .tool-icon {
            font-size: 2rem;
        }
    </style>
</head>
<body class="bg-light text-dark">
<div class="container py-4">

    <!-- Language & Logout -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <span class="me-3"><?= $t['welcome'] ?? 'Welcome' ?>, <?= $_SESSION['email'] ?></span>
            <a href="logout.php" class="btn btn-outline-danger btn-sm"><?= $t['logout'] ?? 'Logout' ?></a>
        </div>
        <div>
            <a href="?lang=en" class="btn btn-primary btn-sm">EN</a>
            <a href="?lang=sk" class="btn btn-primary btn-sm">SK</a>

        </div>
    </div>

    <h2 class="text-center mb-4">PDF Tools</h2>

    <div class="row row-cols-2 row-cols-md-4 g-4">
        <?php
        $tools = [
            ['Merge PDF', 'merge', true],
            ['Split PDF', 'split', true],
            ['Compress PDF', 'compress', true],
            ['Rotate Pages', 'rotate'],
            ['Add Watermark', 'watermark'],
            ['Remove Pages', 'remove', true],
            ['Extract Images', 'extract'],
            ['PDF to Images', 'pdf2img'],
        ];

        foreach ($tools as $tool) {
            $title = $tool[0];
            $action = $tool[1];
            $active = $tool[2] ?? false;

            echo '
    <div class="col">
        <div class="card text-center text-dark h-100 tool-card p-3">
            <div class="tool-icon">📄</div>
            <h6 class="mt-2">' . $title . '</h6>';

            if ($active && $action === 'merge') {
                echo '<a href="#" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#mergeModal">Merge</a>';
            } elseif ($active && $action === 'split') {
                echo '<a href="#" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#splitModal">Split</a>';
            } elseif ($active && $action === 'compress'){
                echo '<a href="#" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#compressModal">Compress</a>';
            }
            else {
                echo '<a href="#" class="btn btn-outline-primary btn-sm mt-2 disabled">Coming soon</a>';
            }




            echo '
        </div>
    </div>';
        }

        ?>
    </div>
</div>


<!-- Merge Modal -->
<div class="modal fade" id="mergeModal" tabindex="-1" aria-labelledby="mergeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="mergeForm" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title" id="mergeLabel">Merge PDF Files</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label>Select PDF files to merge:</label>
                <input type="file" name="files[]" class="form-control mt-2" multiple required accept="application/pdf">
                <div class="alert alert-danger mt-3 d-none" id="mergeError"></div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Merge</button>
            </div>
        </form>
    </div>
</div>


<!-- Split Modal -->
<div class="modal fade" id="splitModal" tabindex="-1" aria-labelledby="splitLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="splitForm" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title" id="splitLabel">Split PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label>Choose PDF file:</label>
                <input type="file" name="file" accept="application/pdf" class="form-control" required>

                <label class="mt-3">Pages to extract (e.g. 1-2,4,6):</label>
                <input type="text" name="pages" class="form-control" required placeholder="1-3,5,6-7">

                <div class="alert alert-danger mt-3 d-none" id="splitError"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Split</button>
            </div>
        </form>
    </div>
</div>


<!-- Compress Modal -->
<div class="modal fade" id="compressModal" tabindex="-1" aria-labelledby="compressLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="compressForm" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title" id="compressLabel">Compress PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label>Choose PDF to compress:</label>
                <input type="file" name="file" accept="application/pdf" class="form-control" required>
                <div class="alert alert-danger mt-3 d-none" id="compressError"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Compress</button>
            </div>
        </form>
    </div>
</div>






<script>
    document.getElementById("mergeForm").addEventListener("submit", async function(e) {
        e.preventDefault();
        const form = e.target;
        const data = new FormData(form);
        const errorBox = document.getElementById("mergeError");
        errorBox.classList.add("d-none");

        try {
            const res = await fetch("api/merge.php", {
                method: "POST",
                body: data
            });

            if (!res.ok) throw new Error("Merge failed");

            const blob = await res.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = "merged.pdf";
            document.body.appendChild(a);
            a.click();
            a.remove();
            bootstrap.Modal.getInstance(document.getElementById('mergeModal')).hide();
        } catch (err) {
            errorBox.innerText = err.message;
            errorBox.classList.remove("d-none");
        }
    });





    document.getElementById("splitForm").addEventListener("submit", async function(e) {
        e.preventDefault();
        const form = e.target;
        const data = new FormData(form);
        const errorBox = document.getElementById("splitError");
        errorBox.classList.add("d-none");

        try {
            const res = await fetch("api/split.php", {
                method: "POST",
                body: data
            });

            if (!res.ok) throw new Error("Split failed");

            const blob = await res.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = "split.pdf";
            document.body.appendChild(a);
            a.click();
            a.remove();
            bootstrap.Modal.getInstance(document.getElementById('splitModal')).hide();
        } catch (err) {
            errorBox.innerText = err.message;
            errorBox.classList.remove("d-none");
        }
    });




    document.getElementById("compressForm").addEventListener("submit", async function(e) {
        e.preventDefault();
        const form = e.target;
        const data = new FormData(form);
        const errorBox = document.getElementById("compressError");
        errorBox.classList.add("d-none");

        try {
            const res = await fetch("api/compress.php", {
                method: "POST",
                body: data
            });

            if (!res.ok) throw new Error("Compression failed");

            const blob = await res.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = "compressed.pdf";
            document.body.appendChild(a);
            a.click();
            a.remove();
            bootstrap.Modal.getInstance(document.getElementById('compressModal')).hide();
        } catch (err) {
            errorBox.innerText = err.message;
            errorBox.classList.remove("d-none");
        }
    });



</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
