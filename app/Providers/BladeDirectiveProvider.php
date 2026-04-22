<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeDirectiveProvider extends ServiceProvider
{
  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    Blade::directive('sessionSuccessMessage', function ($expression) {
      return "<?php if (session($expression)): ?>

                <div class=\"alert alert-success alert-dismissible text-center \">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                        <span aria-hidden=\"true\">&times;</span>
                    </button>
                    <?php echo e(session($expression)); ?>
                </div>
            <?php endif; ?>";
    });

    Blade::directive('sessionErrorMessage', function ($expression) {
      return "<?php if (session($expression)): ?>
                <div class=\"alert alert-danger alert-dismissible text-center \">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                        <span aria-hidden=\"true\">&times;</span>
                    </button>
                    <?php echo e(session($expression)); ?>
                </div>
            <?php endif; ?>";
    });

    Blade::directive('FieldError', function ($expression) {
      return "<?php if (\$errors->has($expression)): ?>
                <span class='block invalid-feedback'><?php echo e(\$errors->first($expression));?></span>
            <?php endif; ?>";
    });
  }

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    //
  }
}
