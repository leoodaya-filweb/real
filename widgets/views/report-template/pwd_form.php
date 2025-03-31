<?php

use app\helpers\App;
use app\helpers\Html;
use yii\helpers\Inflector;

$form = App::params('pwd_form');
?>
<div style="font-family: arial, helvetica, sans-serif;">
    <div style="box-sizing: border-box; color: #3f4254; font-size: 14pt; background-color: #ffffff; flex-wrap: nowrap !important; -webkit-box-pack: justify !important; justify-content: space-between !important; -webkit-box-align: center !important; align-items: center !important;">
        <div style="box-sizing: border-box;">
            [HEADER_PWD]
            <div style="box-sizing: border-box; padding-right: 0 !important; padding-left: 0 !important;">



                <div style="box-sizing: border-box; margin-top: 1rem !important; text-align: justify !important; font-size: 14pt !important; line-height: 1.75;" >
                    <table style="border-collapse: collapse; width: 100%;" border="1">
                        <tbody>
                            <tr>
                                <td style="width: 45%;padding: 0px 0.4rem;">
                                    <p style="margin-top: 0; margin-bottom: 1.5rem;"> 
                                        <span style="font-size: 14pt;">1.</span>

                                        <label>
                                            <input type="radio" id="pwd_type-<?= Inflector::slug($form['type'][0]) ?>" name="pwd_type" >
                                            <strong>
                                                <span style="font-size: 14pt;">
                                                    <?= $form['type'][0] ?>
                                                </span>
                                            </strong>
                                        </label>

                                    </p>
                                </td>
                                <td style="padding: 0px 0.4rem;width: 45%;" colspan="2">
                                    <p style="margin-top: 0; margin-bottom: 1.5rem;">
                                        <label>
                                            <input type="radio" id="pwd_type-<?= Inflector::slug($form['type'][1]) ?>" name="pwd_type" >
                                        <span style="font-size: 14pt;"><strong><?= $form['type'][1] ?></strong> *</span>
                                        </label>
                                    </p>
                                </td>
                                <td style="width: 10%;padding: 0;" rowspan="2">
                                    <div style="margin: auto; height: 9rem;">[PHOTO]</div>
                                </td>
                            </tr>
                            <tr style="line-height: 30px;">
                                <td style="padding: 0px 0.4rem;width: 60%;" colspan="2">
                                    <p style="margin-top: 0; margin-bottom: 0;"><strong><span style="font-size: 14pt;">2. PERSONS WITH DISABILITY NUMBER (RR-PPMM-BBB-NNNNNNN) </span></strong><span style="font-size: 14pt;">*</span></p>
                                    <div style="text-align: center;"><span style="font-size: 14pt;">[PWD_ID_NO]</span></div>
                                </td>
                                <td style="width: 30%;vertical-align: top;padding: 0px 0.4rem;">
                                    <span style="font-size: 14pt;"><strong>3. Date Applied</strong> * (mm/dd/yyyy) </span>
                                    <div style="text-align: center;"><span style="font-size: 14pt;">[DATE_APPLIED]</span></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-collapse: collapse; width: 100%; height: 29px; border-top: hidden;" border="1">
                        <tbody>
                            <tr style="
                                ">
                                <td style="width: 97.3126%;padding: 0px 0.4rem;"><strong><span style="font-size: 14pt;">4. PERSONAL INFORMATION </span></strong><span style="font-size: 14pt;">*</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-collapse: collapse; width: 100%; height: 48px; border-top: hidden;" border="1">
                        <tbody>
                            <tr style="height: 38px;line-height: 30px;">
                                <td style="width: 25%;padding: 0px 0.4rem;height: 38px;" colspan="2">
                                    <p style="margin-top: 0px; margin-bottom: 0px; text-align: center;"><span style="font-size: 14pt;"><strong>LAST NAME:</strong> *</span></p>
                                    <p style="margin-bottom: 0px; margin-top: 0px; text-align: center;"><span style="font-size: 14pt;">[LAST_NAME]</span></p>
                                </td>
                                <td style="width: 25%;padding: 0px 0.4rem;text-align: center;height: 38px;">
                                    <p style="margin-top: 0; margin-bottom: 0;"><span style="font-size: 14pt;"><strong>FIRST NAME:</strong> *</span></p>
                                    <p style="margin-bottom: 0; margin-top: 0;"><span style="font-size: 14pt;">[FIRST_NAME]</span></p>
                                </td>
                                <td style="width: 25%;padding: 0px 0.4rem;text-align: center;height: 38px;" colspan="2">
                                    <p style="margin-top: 0; margin-bottom: 0;"><span style="font-size: 14pt;"><strong>MIDDLE NAME:</strong> *</span><br></p>
                                    <p style="margin-bottom: 0; margin-top: 0;"><span style="font-size: 14pt;">[MIDDLE_NAME]</span></p>
                                </td>
                                <td style="width: 25%;padding: 0px 0.4rem;text-align: center;height: 38px;" colspan="2">
                                    <p style="margin-top: 0; margin-bottom: 0;"><span style="font-size: 14pt;"><strong>SUFFIX:</strong> *</span></p>
                                    <p style="margin-bottom: 0; margin-top: 0;"><span style="font-size: 14pt;">[SUFFIX]</span></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-collapse: collapse; width: 100%; border-top: hidden; height: 10px;" border="1">
                        <tbody>
                            <tr style="height: 10px;">
                                <td style="padding: 0px 0.4rem; width: 30%; height: 10px; vertical-align: top; border-right: hidden;"><span style="font-size: 14pt;"><strong>5. DATE OF BIRTH:</strong> * (mm/dd/yyyy)</span></td>
                                <td style="padding: 0px 0.4rem;height: 10px;width: 20%;" colspan="2"><span style="font-size: 14pt;">[BIRTHDATE]</span><br></td>
                                <td style="padding: 0px 0.4rem; height: 10px; width: 25%;">
                                    <div style="margin-top: 0; margin-bottom: 0; line-height: 20px;"><span style="font-size: 14pt;">6. SEX: *</span></div>
                                    <div style="margin-top: 0px;margin-bottom: 5px;text-align: center;line-height: 20px;"><label>
                                        <input type="radio" id="gender-<?= Inflector::slug($form['sex'][0]) ?>" name="gender" >

                                        <span style="font-size: 14pt;text-transform: uppercase;"><?= $form['sex'][0] ?></span>
                                        </label>
                                    </div>
                                </td>
                                <td style="width: 25%;padding: 0px 0.4rem;vertical-align: bottom;height: 10px;">

                                    <label>
                                    <input type="radio" id="gender-<?= Inflector::slug($form['sex'][1]) ?>" name="gender" >
                                    <span style="font-size: 14pt;text-transform: uppercase;"><?= $form['sex'][1] ?></span>
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-collapse: collapse; width: 100%; height: 54px; border-top: hidden;" border="1">
                        <tbody>
                            <tr style="
                                ">
                                <td style="width: 16.4617%; padding: 0px 0.4rem;border-bottom: hidden;" colspan="5"><span style="font-size: 14pt;"><strong>7. CIVIL STATUS:</strong> *</span><br></td>
                            </tr>
                            <tr style="">
                                <?= Html::foreach($form['civil_status'], function($cs) {
                                    $slug = Inflector::slug($cs);
                                    $style = $slug == 'widower' ? '': 'border-right: hidden';
                                    return <<< HTML
                                        <td style="width: 20%; padding: 0px 0.4rem; vertical-align: top;{$style}">
                                        <label>
                                        <input type="radio" id="civil_status-{$slug}" name="civil_status" >
                                        <span style="font-size: 14pt;">{$cs}</span>
                                        </label>
                                    </td>
                                    HTML;
                                }) ?>

                            </tr>
                        </tbody>
                    </table>
                    <table style="border-collapse: collapse; width: 100%; border-top: hidden;" border="1">
                        <tbody>
                            <tr style="">
                                <td style="width: 60%;padding: 0 0.4rem;border-bottom: hidden;" colspan="2" scope="row"><span style="font-size: 14pt;"><strong>8. TYPE OF DISABILITY:</strong> *</span><br></td>
                                <td style="width: 40%;padding: 0 0.4rem;border-bottom: hidden;" colspan="2"><span style="font-size: 14pt;">9. <strong>CAUSE OF DISABILITY:</strong> *</span></td>
                            </tr>
                            <tr style="line-height: 22px;">
                                <td style="width: 23.7446%; padding: 0 0.4rem; line-height: 22px;border-right: hidden;">

                                    <?= Html::foreach($form['type_of_disability'][0], function($d) {
                                        $slug = Inflector::slug($d);
                                        return <<< HTML
                                            <div style="text-indent: 30px;">
                                                <label>
                                                    <input type="checkbox" id="type_of_disability-{$slug}" name="type_of_disability" >
                                                    <span style="font-size: 14pt;">
                                                        {$d}
                                                    </span>
                                                </label>
                                            </div>
                                        HTML;
                                    }) ?>

                                </td>
                                <td style="width: 27.0918%; padding: 0 0.4rem; line-height: 22px;">
                                    <?= Html::foreach($form['type_of_disability'][1], function($d) {
                                        $slug = Inflector::slug($d);
                                        return <<< HTML
                                            <div>
                                                <label>
                                                    <input type="checkbox" id="type_of_disability-{$slug}" name="type_of_disability" >
                                                    <span style="font-size: 14pt;">
                                                        {$d}
                                                    </span>
                                                </label>
                                            </div>
                                        HTML;
                                    }) ?>
                                </td>
                                <td style="width: 24.4371%; padding: 0 0.4rem; line-height: 22px;border-right: hidden;">
                                    <div>
                                        <label>
                                            <input type="checkbox" id="cause_of_disability-<?= Inflector::slug($form['cause_of_disability'][0]['type']) ?>">
                                            <span style="font-size: 14pt;">
                                                <?= $form['cause_of_disability'][0]['type'] ?>
                                            </span>
                                        </label>
                                    </div>

                                    <?= Html::foreach($form['cause_of_disability'][0]['cause'], function($d) use($form) {
                                        $slug = implode('-', [
                                            Inflector::slug($form['cause_of_disability'][0]['type']),
                                            Inflector::slug($d)
                                        ]);
                                        return <<< HTML
                                            <div style="text-indent: 30px;">
                                                <label>
                                                    <input type="checkbox" id="cause_of_disability-{$slug}">
                                                    <span style="font-size: 14pt;">
                                                        {$d}
                                                    </span>
                                                </label>
                                            </div>
                                        HTML;
                                    }) ?>
                                </td>
                                <td style="width: 18.9217%; vertical-align: top; padding: 0 0.4rem; line-height: 22px;">
                                    <div>
                                        <label>
                                            <input type="checkbox" id="cause_of_disability-<?= Inflector::slug($form['cause_of_disability'][1]['type']) ?>">
                                            <span style="font-size: 14pt;">
                                                <?= $form['cause_of_disability'][1]['type'] ?>
                                            </span>
                                        </label>
                                    </div>

                                    <?= Html::foreach($form['cause_of_disability'][1]['cause'], function($d) use($form) {
                                        $slug = implode('-', [
                                            Inflector::slug($form['cause_of_disability'][1]['type']),
                                            Inflector::slug($d)
                                        ]);
                                        return <<< HTML
                                            <div style="text-indent: 30px;">
                                                <label>
                                                    <input type="checkbox" id="cause_of_disability-{$slug}">
                                                    <span style="font-size: 14pt;">
                                                        {$d}
                                                    </span>
                                                </label>
                                            </div>
                                        HTML;
                                    }) ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-collapse: collapse; width: 100%; border-top: hidden;" border="1">
                        <tbody>
                            <tr style="">
                                <td style="width: 99.9935%; padding: 0px 0.4rem;" colspan="5"><span style="font-size: 14pt;"><strong>10. RESIDENCE ADDRESS</strong> *</span><br></td>
                            </tr>
                            <tr style="">
                                <td style="padding: 0px 0.4rem;width: 20%;">
                                    <span style="font-size: 14pt;"><strong>House No. and Street:</strong> *</span>
                                    <div style="font-size: 14pt; text-align: center;">[HOUSE_NO] [STREET]</div>
                                </td>
                                <td style="width: 20%; padding: 0 0.4rem;">
                                    <span style="font-size: 14pt;"><strong>Barangay:</strong> *</span>
                                    <div style="font-size: 14pt; text-align: center;">[BARANGAY]</div>
                                </td>
                                <td style="width: 20%; padding: 0 0.4rem;">
                                    <span style="font-size: 14pt;"><strong>Municipality:</strong> *</span>
                                    <div style="font-size: 14pt; text-align: center;">[MUNICIPALITY]</div>
                                </td>
                                <td style="width: 20%; padding: 0 0.4rem;">
                                    <span style="font-size: 14pt;"><strong>Province:</strong> *</span>
                                    <div style="font-size: 14pt; text-align: center;">[PROVINCE]</div>
                                </td>
                                <td style="width: 20%; padding: 0 0.4rem;">
                                    <span style="font-size: 14pt;"><strong>Region:</strong> *</span>
                                    <div style="font-size: 14pt; text-align: center;">[REGION]</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-collapse: collapse; width: 100%; height: 367px; border-top: hidden;" border="1">
                        <tbody>
                            <tr style="height: 35px;">
                                <td style="padding: 0px 0.4rem; width: 97.5636%; height: 35px;" colspan="3"><span style="font-size: 14pt;"><span style="font-size: 14pt;"><strong>11. CONTACT DETAILS *</strong></span></span><span style="font-size: 14pt;"><span style="font-size: 14pt;"><strong><br></strong></span></span></td>
                            </tr>
                            <tr style="line-height: 22px; height: 46px;">
                                <td style="padding: 0px 0.4rem;width: 30%;height: 46px;">
                                    <span style="font-size: 14pt;"><span style="font-size: 14pt;"><strong>Landline no.:&nbsp;</strong></span></span>
                                    <div style="font-size: 14pt; text-align: center;">[LANDLINE_NO]</div>
                                </td>
                                <td style="padding: 0px 0.4rem;width: 30%;height: 46px;">
                                    <span style="font-size: 14pt;"><span style="font-size: 14pt;"><strong>Mobile No.:</strong></span></span>
                                    <div style="font-size: 14pt; text-align: center;">[MOBILE_NO]</div>
                                </td>
                                <td style="width: 40%;padding: 0px 0.4rem;height: 46px;">
                                    <span style="font-size: 14pt;"><span style="font-size: 14pt;"><strong>Email Address:</strong></span></span>
                                    <div style="font-size: 14pt; text-align: center;">[EMAIL]</div>
                                </td>
                            </tr>
                            <tr style="height: 35px;">
                                <td style="padding: 0px 0.4rem; width: 60%; height: 35px;border-bottom: hidden;" colspan="2"><span style="font-size: 14pt;"><strong>12. EDUCATIONAL ATTAINMENT:</strong> *</span><br></td>

                                <td style="width: 40%; padding: 0px 0.4rem; height: 35px;border-bottom: hidden;"><span style="font-size: 14pt;"><strong>14. OCCUPATION:</strong> *</span></td>

                            </tr>
                            <tr style="height: 99px; line-height: 22px;">
                                <td style="width: 31.0924%; height: 99px; padding: 0px 0.4rem;border-right: hidden;">
                                    
                                    <?= Html::foreach($form['educational_attainment'][0], function($d) {
                                        $slug = Inflector::slug($d);
                                        return <<< HTML
                                            <div style="text-indent: 20px;">
                                                <label>
                                                    <input type="radio" id="educ_attainment-{$slug}" name="educ_attainment">
                                                    <span style="font-size: 14pt;">{$d}</span>
                                                </label>
                                            </div>
                                        HTML;
                                    }) ?>
                                </td>
                                <td style="width: 28.0113%; height: 99px; padding: 0px 0.4rem;">
                                    <?= Html::foreach($form['educational_attainment'][1], function($d) {
                                        $slug = Inflector::slug($d);
                                        return <<< HTML
                                            <div style="text-indent: 20px;">
                                                <label>
                                                    <input type="radio" id="educ_attainment-{$slug}" name="educ_attainment">
                                                    <span style="font-size: 14pt;">{$d}</span>
                                                </label>
                                            </div>
                                        HTML;
                                    }) ?>
                                </td>
                                <td style="width: 38.4599%; height: 251px; padding: 0; vertical-align: top;" rowspan="4">

                                    <?= Html::foreach($form['occupation'], function($d) use($form) {
                                        $slug = Inflector::slug($d);
                                        return <<< HTML
                                            <div style="text-indent: 20px;"><label>
                                                <input type="radio" id="occupation-{$slug}" name="occupation">
                                                <span style="font-size: 14pt;">{$d}</span>
                                                </label>
                                            </div>
                                        HTML;
                                    }) ?>
                                     <div style="text-indent: 20px;"><label>
                                        <input type="radio" id="occupation-others" name="occupation">
                                        <span style="font-size: 14pt;">Others, specify <span style="text-decoration: underline;">[OTHERS]</span></span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr style="height: 35px;">
                                <td style="width: 31.0924%; padding: 0px 0.4rem; height: 35px;border-bottom: hidden;"><span style="font-size: 14pt;"><strong>13. STATUS OF EMPLOYMENT:</strong> *</span></td>

                                <td style="width: 28.0113%; padding: 0px 0.4rem; height: 35px;border-bottom: hidden;"><span style="font-size: 14pt;"><strong>13 b. TYPES OF EMPLOYMENT:</strong> *</span></td>

                            </tr>
                            <tr style="line-height: 22px; height: 72px;">
                                <td style="width: 31.0924%; vertical-align: top; height: 72px; padding: 0px 0.4rem;">

                                    <?= Html::foreach($form['status_of_employment'], function($d) use($form) {
                                        $slug = Inflector::slug($d);
                                        return <<< HTML
                                            <div style="text-indent: 20px;"><label>
                                                <input type="radio" id="status_of_employment-{$slug}" name="status_of_employment">
                                                <span style="font-size: 14pt;">{$d}</span>
                                                </label>
                                            </div>
                                        HTML;
                                    }) ?>

                                </td>
                                <td style="width: 28.0113%; vertical-align: top; height: 117px; padding: 0px 0.4rem;" rowspan="2">

                                    <?= Html::foreach($form['types_of_employment'], function($d) use($form) {
                                        $slug = Inflector::slug($d);
                                        return <<< HTML
                                            <div style="text-indent: 20px;"><label>
                                                <input type="radio" id="types_of_employment-{$slug}" name="types_of_employment">
                                                <span style="font-size: 14pt;">{$d}</span>
                                                </label>
                                            </div>
                                        HTML;
                                    }) ?>
                                </td>
                            </tr>
                            <tr style="height: 45px; line-height: 22px;">
                                <td style="width: 31.0924%; height: 45px; padding: 0px 0.4rem; vertical-align: top;">
                                    <div style="text-indent: 20px;"><span style="font-size: 14pt;"><strong>13 a. CATEGORY OF EMPLOYMENT:</strong> *</span></div>

                                    <?= Html::foreach($form['category_of_employment'], function($d) use($form) {
                                        $slug = Inflector::slug($d);
                                        return <<< HTML
                                            <div style="text-indent: 20px;"><label>
                                                <input type="radio" id="category_of_employment-{$slug}" name="category_of_employment">
                                                <span style="font-size: 14pt;">{$d}</span>
                                                </label>
                                            </div>
                                        HTML;
                                    }) ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-collapse: collapse; border-top: hidden; width: 100%;" border="1">
                        <tbody>
                            <tr style="">
                                <td style="padding: 0px 0.4rem; width: 66.48%;" colspan="4"><span style="font-size: 14pt;"><strong>15. ORGANIZATION INFORMATION:</strong></span><span style="font-size: 14pt;"><strong><br></strong></span></td>
                            </tr>
                            <tr style="">
                                <td style="padding: 0px 0.4rem;width: 25%;">
                                    <span style="font-size: 14pt;"><strong>Organization Affiliated:</strong></span>
                                    <div style="font-size: 14pt;text-align: center;">[ORGANIZATION_AFFILIATED]</div>
                                </td>
                                <td style="padding: 0px 0.4rem;width: 25%;">
                                    <span style="font-size: 14pt;"><strong>Contact Person:</strong></span>
                                    <div style="font-size: 14pt;text-align: center;">[CONTACT_PERSON]</div>
                                </td>
                                <td style="padding: 0px 0.4rem;width: 25%;">
                                    <span style="font-size: 14pt;"><strong>Office Address:</strong></span>
                                    <div style="font-size: 14pt;text-align: center;">[OFFICE_ADDRESS]</div>
                                </td>
                                <td style="padding: 0px 0.4rem;width: 25%;">
                                    <span style="font-size: 14pt;"><strong>Tel. No.:</strong></span>
                                    <div style="font-size: 14pt;text-align: center;">[TEL_NO]</div>
                                </td>
                            </tr>
                            <tr style="">
                                <td style="padding: 0px 0.4rem; width: 66.48%;" colspan="4"><span style="font-size: 14pt;"><strong>16. ID REFERENCE NO.:</strong></span></td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-collapse: collapse; width: 100%; border-top: hidden;" border="1">
                        <tbody>
                            <tr style="">
                                <td style="width: 20%;padding: 0 0.4rem;">
                                    <span style="font-size: 14pt;"><strong>SSS NO.:</strong></span>
                                    <div style="font-size: 14pt;text-align: center;">[SSS]</div>
                                </td>
                                <td style="width: 20%;padding: 0 0.4rem;">
                                    <span style="font-size: 14pt;"><strong>GSIS NO.:</strong></span>
                                    <div style="font-size: 14pt;text-align: center;">[GSIS]</div>
                                </td>
                                <td style="width: 20%;padding: 0 0.4rem;">
                                    <span style="font-size: 14pt;"><strong>PAG-IBIG NO.:</strong></span>
                                    <div style="font-size: 14pt;text-align: center;">[OFFICE_ADDRESS]</div>
                                </td>
                                <td style="width: 20%;padding: 0 0.4rem;">
                                    <span style="font-size: 14pt;"><strong>PSN NO.:</strong></span>
                                    <div style="font-size: 14pt;text-align: center;">[PSN_NO]</div>
                                </td>
                                <td style="width: 20%;padding: 0 0.4rem;">
                                    <span style="font-size: 14pt;"><strong>PhilHealth No.:</strong></span>
                                    <div style="font-size: 14pt;text-align: center;">[PHILHEALTH_NO]</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-collapse: collapse; width: 100%; border-top: hidden; height: 525px;" border="1"><tbody><tr style="height: 35px;"><td style="width: 34%; padding: 0px 0.4rem; height: 35px;" colspan="2"><span style="font-size: 14pt;"><strong>17.FAMILY BACKGROUND:</strong></span><span style="font-size: 14pt;"><strong><br></strong></span></td><td style="width: 22%; padding: 0px 0.4rem; height: 35px;"><strong><span style="font-size: 14pt;">LAST NAME</span></strong></td><td style="width: 22%; padding: 0px 0.4rem; height: 35px;"><strong><span style="font-size: 14pt;">FIRST NAME</span></strong></td><td style="width: 22%; padding: 0px 0.4rem; height: 35px;"><strong><span style="font-size: 14pt;">MIDDLE NAME</span></strong></td></tr><tr style="height: 35px;"><td style="width: 13.2681%; text-align: right; padding: 0px 0.4rem; height: 35px;"><br></td><td style="width: 16.0196%; padding: 0px 0.4rem; text-align: left; height: 35px;"><span style="font-size: 14pt;"><em>FATHER'S NAME:</em></span></td><td style="width: 18.0922%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[F_LAST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[F_FIRST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[F_MIDDLE_NAME]</span></td></tr><tr style="height: 35px;"><td style="width: 13.2681%; text-align: right; padding: 0px 0.4rem; height: 35px;"><br></td><td style="width: 16.0196%; padding: 0px 0.4rem; text-align: left; height: 35px;"><span style="font-size: 14pt;"><em>MOTHERS NAME:</em></span></td><td style="width: 18.0922%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[M_LAST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[M_FIRST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[M_MIDDLE_NAME]</span></td></tr><tr style="height: 35px;"><td style="width: 13.2681%; text-align: right; padding: 0px 0.4rem; height: 35px;"><br></td><td style="width: 16.0196%; padding: 0px 0.4rem; text-align: left; height: 35px;"><span style="font-size: 14pt;"><em>GUARDIAN:</em></span></td><td style="width: 18.0922%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[G_LAST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[G_FIRST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[G_MIDDLE_NAME]</span></td></tr><tr style="height: 35px;"><td style="padding: 0px 0.4rem; text-align: left; width: 29.2877%; height: 35px;" colspan="2"><span style=""><span style="font-size: 14pt;">1</span><strong style="font-size: 14pt;"><span style="font-size: 14pt;">8. ACCOMPLISHED BY: </span>*</strong></span><span style="font-size: 14pt;"><em><br></em></span></td><td style="width: 18.0922%; padding: 0px 0.4rem; height: 35px;"><strong><span style="font-size: 14pt;">LAST NAME</span></strong></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><strong><span style="font-size: 14pt;">FIRST NAME</span></strong></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><strong><span style="font-size: 14pt;">MIDDLE NAME</span></strong></td></tr><tr style="height: 35px;"><td style="padding: 0px 0.4rem; text-align: left; width: 13.2681%; height: 35px;"><span style=""><span style="font-size: 14pt;"><br></span></span></td><td style="padding: 0px 0.4rem; text-align: left; width: 16.0196%; height: 35px;">
                        <label>
                            <input type="radio" id="accomplished_by-<?= Inflector::slug($form['accomplished_by'][0]) ?>" name="accomplished_by">
                            <span style="">
                                <span style="font-size: 14pt;">
                                    <em>
                                        <strong style="text-transform: uppercase;"><?= $form['accomplished_by'][0] ?></strong>
                                    </em>
                                </span>
                            </span>
                        </label>

                    </td><td style="width: 18.0922%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[A_LAST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[A_FIRST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[A_MIDDLE_NAME]</span></td></tr><tr style="height: 35px;"><td style="padding: 0px 0.4rem; text-align: left; width: 13.2681%; height: 35px;"><span style=""><span style="font-size: 14pt;"><br></span></span></td>
                        <td style="padding: 0px 0.4rem; text-align: left; width: 16.0196%; height: 35px;">
                            <label>
                                <input type="radio" id="accomplished_by-<?= Inflector::slug($form['accomplished_by'][1]) ?>" name="accomplished_by">
                                <span style="">
                                    <span style="font-size: 14pt;">
                                        <em>
                                            <strong style="text-transform: uppercase;"><?= $form['accomplished_by'][1] ?></strong>
                                        </em>
                                    </span>
                                </span>
                            </label>

                    </td><td style="width: 18.0922%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[AG_LAST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[AG_FIRST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[AG_MIDDLE_NAME]</span></td></tr><tr style="height: 35px;"><td style="padding: 0px 0.4rem; text-align: left; width: 13.2681%; height: 35px;"><span style=""><span style="font-size: 14pt;"><br></span></span></td>
                        <td style="padding: 0px 0.4rem; text-align: left; width: 16.0196%; height: 35px;">
                            <label>
                                <input type="radio" id="accomplished_by-<?= Inflector::slug($form['accomplished_by'][2]) ?>" name="accomplished_by">
                                <span style="">
                                    <span style="font-size: 14pt;">
                                        <em>
                                            <strong style="text-transform: uppercase;"><?= $form['accomplished_by'][2] ?></strong>
                                        </em>
                                    </span>
                                </span>
                            </label>

                    </td><td style="width: 18.0922%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[R_LAST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[R_FIRST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[R_MIDDLE_NAME]</span></td></tr><tr style="height: 35px;"><td style="padding: 0px 0.4rem; text-align: left; width: 29.2877%; height: 35px;" colspan="2"><strong><span style="font-size: 14pt;">19. NAME OF CERTIFYING PHYSICIAN</span></strong><span style=""><span style="font-size: 14pt;"><em><br></em></span></span></td><td style="width: 18.0922%; padding: 0px 0.4rem; height: 70px; text-align: left; vertical-align: middle;" rowspan="2"><span style="font-size: 14pt;">[P_LAST_NAME]﻿</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 70px; text-align: left; vertical-align: middle;" rowspan="2"><span style="font-size: 14pt;">[P_FIRST_NAME]﻿</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 70px; text-align: left; vertical-align: middle;" rowspan="2"><span style="font-size: 14pt;">[P_MIDDLE_NAME]</span></td></tr><tr style="height: 35px;"><td style="padding: 0px 0.4rem; width: 13.2681%; text-align: right; height: 35px;"><em><strong><span style="font-size: 14pt;">LICENSE NO.:</span></strong></em></td><td style="padding: 0px 0.4rem; text-align: left; width: 16.0196%; height: 35px;"><span style=""><span style="font-size: 14pt;">[LICENSE_NO]</span></span></td></tr><tr style="height: 35px;"><td style="padding: 0px 0.4rem; text-align: left; height: 35px;" colspan="2"><strong><span style="font-size: 14pt;">20. </span><span style="font-size: 14pt;">PROCESSING OFFICER:</span><span style="font-size: 14pt;"> *</span></strong><span style=""><span style="font-size: 14pt;"><br></span></span></td><td style="width: 18.0922%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[PO_LAST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[PO_FIRST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[PO_MIDDLE_NAME]</span></td></tr><tr style="height: 35px;"><td style="padding: 0px 0.4rem; text-align: left; height: 35px;" colspan="2"><strong><span style="font-size: 14pt;">21. APPROVING OFFICER: *</span></strong></td><td style="width: 18.0922%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[AO_LAST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[AO_FIRST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[AO_MIDDLE_NAME]</span></td></tr><tr style="height: 35px;"><td style="padding: 0px 0.4rem; text-align: left; height: 35px;" colspan="2"><strong><span style="font-size: 14pt;">22. ENCODER *</span></strong></td><td style="width: 18.0922%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[E_LAST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[E_FIRST_NAME]</span></td><td style="width: 23.6899%; padding: 0px 0.4rem; height: 35px;"><span style="font-size: 14pt;">[E_MIDDLE_NAME]</span></td></tr><tr style="height: 35px;"><td style="padding: 0px 0.4rem; text-align: left; height: 35px;" colspan="5"><strong><span style="font-size: 14pt;">23. NAME OF REPORTING UNIT: (OFFICE/SECTION): * </span></strong><span style="font-size: 14pt;">[REPORTING_UNIT]</span><strong><span style="font-size: 14pt;"><br></span></strong></td></tr><tr style="height: 35px;"><td style="padding: 0px 0.4rem; width: 13.2681%; text-align: left; height: 35px;" colspan="5"><strong><span style="font-size: 14pt;">24. CONTROL NO.: * </span></strong><span style="font-size: 14pt;">[CONTROL_NO]</span></td></tr></tbody></table>
                    <div style="
                        font-size: 14pt;
                        text-align: right;
                        margin-top: 2rem;
                    ">
                        <p style="border: 1px solid #ccc;padding: 0.5rem 1rem;width: max-content;float: right;">Revised as of [REVISED_DATE]</p>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>