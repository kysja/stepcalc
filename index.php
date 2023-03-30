<?php 
require_once 'ShoreStepForm.php';
require_once 'ShoreStepCalculator.php';
$form = new ShoreStepForm();

if ($_POST['k5_calculate'] ?? false) {
    $form->process($_POST);
    $calc = new ShoreStepCalculator($form->rise, $form->run, $form->pf_count);
    $svg = $calc->generateSvg();
    $results = $calc->getResults($form);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shore Step Calculator</title>
    <link rel="stylesheet" href="./resources/css/bootstrap.css">
    <link rel="stylesheet" href="./resources/css/k5style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata&display=swap" rel="stylesheet"></head>
<body>
<div class="container">

    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="./" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
            <img src="./resources/images/logo.png" alt="" class="me-4">
            <span class="fs-4 fw-bold">Shore Step Calculator</span>
        </a>
    </header>



    <div class="row">
        <div class="col-12 col-lg-4">

            <p>This calculator works best for visualizing straight runs of shore steps. </p>

            <div class="k5_test_data"><img src="/resources/images/fillform.png" class="me-2"><span onclick="testdata();">Fill out the form with the test data</span></div>

            <div class="k5_form">
                <form method="post" action="">

                    <h2>Project Information</h2>
                    <div class="k5_elem2">
                        Rise: <?= $form->showInput('rise_ft', 'k5_inp_num') ?> ft. &nbsp; <?= $form->showInput('rise_in', 'k5_inp_num') ?> in.
                        <br>
                        &nbsp;Run: <?= $form->showInput('run_ft', 'k5_inp_num') ?> ft. &nbsp; <?= $form->showInput('run_in', 'k5_inp_num') ?> in.            
                    </div>

                    <h2>Platforms</h2>
                    <div class="k5_elem2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="platforms" name="platforms" value="Yes" <?= ($form->fields['platforms']['value'] ?? false) === 'Yes' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="switchPlatforms">Check if you need platforms</label>
                        </div>
                        <div id="divPlatformsQty" style="<?= ($form->fields['platforms']['value'] ?? false) !== 'Yes' ? 'display:none;' : '' ?>">
                            <h3 class="mt-4">Enter Platform Quantities:</h3>
                            <?= $form->showInputPlatforms('platforms_4') ?>
                            <?= $form->showInputPlatforms('platforms_6') ?>
                            <?= $form->showInputPlatforms('platforms_8') ?>
                        </div>
                    </div>

                    <h2>Railings</h2>
                    <div class="k5_elem2">
                        <p>Choose the side your railing will be on</p>
                        <?= $form->showRadio('railings', ['Left', 'Right', 'Both Sides', 'None'], 'form-check-input', false) ?>
                    </div>

                    <h2>Step Color</h2>
                    <div class="k5_elem2">
                        <?= $form->showRadio('step_color', ['White', 'Beige', 'Gray'], 'form-check-input') ?>
                    </div>

                    <div class="k5_elem2">
                        <input type="submit" class="btn btn-primary" name="k5_calculate" value="Calculate">
                        <input type="button" class="btn btn-outline-secondary" value="Reset" onclick="location.href = './';">
                    </div>

                </form>
            </div>

        </div>
        <div class="col-12 col-lg-8">

            <?php if ($_POST['k5_calculate'] ?? false): ?>
                <?= $svg ?>

                <div class="k5_results">
                    <table>
                        <tr>
                            <td colspan=3><h3 style="padding:0;margin:0;">Hill</h3></td>
                        </tr>
                        <tr>
                            <td><b>Rise</b></td>
                            <td><b>Run</b></td>
                            <td><b>Distance</b></td>
                        </tr>
                        <tr>
                            <td><?= $results['rise'][1] ?></td>
                            <td><?= $results['run'][1] ?></td>
                            <td><?= $results['distance'][1] ?></td>
                        </tr>
                        <tr>
                            <td colspan=3><h3 style="padding:0;margin:0;">Steps</h3></td>
                        </tr>
                        <tr>
                            <td><b>Rise</b></td>
                            <td><b>Run</b></td>
                            <td><b>Distance</b></td>
                        </tr>
                        <tr>
                            <td><?= $results['rise'][1] ?></td>
                            <td><?= $results['srun'][1] ?></td>
                            <td><?= $results['sdistance'][1] ?></td>
                        </tr>
                        <tr>
                            <td><b>Step Rise</b></td>
                            <td><b>Step Tread</b></td>
                            <td><b>Step Apart</b></td>
                        </tr>
                        <tr>
                            <td><?= $results['step_rise'][1] ?></td>
                            <td><?= $results['step_run'][1] ?></td>
                            <td><?= $results['step_apart'][1] ?></td>
                        </tr>
                        <tr>
                            <td><b># Steps</b></td>
                            <td><b>Slope</b></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><?= $results['steps'][1] ?></td>
                            <td><?= $results['slope'][1] ?></td>
                            <td></td>
                        </tr>
                    </table>
                </div>

                <p><b>Platforms:</b> <?= $results['platforms'][1] ?></p>
                <p><b>Railings:</b> <?= $results['railings'][1] ?></p>
                <p><b>Step Color:</b> <?= $results['color'][1] ?></p>

            <?php endif; ?>

        </div>
    </div>


</div>

<script src="./resources/js/k5main.js"></script>

</body>
</html>