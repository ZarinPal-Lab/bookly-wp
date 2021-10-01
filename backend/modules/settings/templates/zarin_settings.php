<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly
use Bookly\Backend\Components\Settings\Inputs;
use Bookly\Backend\Components\Settings\Selects;
use Bookly\Backend\Components\Controls\Elements;
use BooklyPro\Lib;
?>
<div class="card bookly-collapse" data-slug="zarin">
    <div class="card-header d-flex align-items-center">
        <?php Elements::renderReorder() ?>
        <a href="#bookly_pmt_zarin" class="ml-2" role="button" data-toggle="collapse">
            زرین پال
        </a>
        <img class="ml-auto" src="<?php echo plugins_url('frontend/resources/images/zarinpal.png', Lib\Plugin::getMainFile()) ?>" />
    </div>
    <div id="bookly_pmt_zarin" class="collapse show">
        <div class="card-body">
            <div class="form-group">
                <?php Selects::renderSingle('bookly_pmt_zarin', null, null, array(array('disabled', __('Disabled', 'bookly')), array('enabled', __('Enable Zarin Pal', 'bookly')))) ?>
            </div>
            <div class="bookly-zarin">
                <div class="bookly-zarin-ec">
                    <?php Inputs::renderText('bookly_pmt_zarin_merchantid', __('API Marchant ID', 'bookly')) ?>
                  
                </div>
            </div>
        </div>
    </div>
</div>