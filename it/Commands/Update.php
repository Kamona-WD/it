<?php
namespace Phpanonymous\It\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class Update extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'it:update {option?}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update New files';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		// $arguments = $this->arguments();
		// dd($arguments);
		$this->warn('Any updates will not affect your previous files, it only works with the new features');
		$ASK_UPDATE = $this->confirm('do you want apply patch update to publish new files from version ' . it_version() . ' ?');

		if ($ASK_UPDATE) {
			if (file_exists(__DIR__ . '/../patch_update/resources/views/admin/ajax.baboon')) {

				$original_path = __DIR__ . '/../patch_update/resources/views/admin/ajax.baboon';
				$new_path = 'resources/views/admin/ajax.blade.php';

				$ajax_baboon = file_get_contents($original_path);
				if (\Storage::disk('it')->put($new_path, $ajax_baboon)) {
					$this->info('Files have been updated successfully');
				} else {
					$this->warn('Update Failed');
				}
			}

		} else {
			$this->warn('The Update is Canceled');
		}

	}

}
