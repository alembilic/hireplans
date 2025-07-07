<?php
namespace Database\Factories\Orchid\Attachment\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchid\Attachment\Models\Attachment;

class AttachmentFactory extends Factory
{
    protected $model = Attachment::class;

    public function definition()
    {
        return [
            // Define the default state of the attachment model.
            'name' => $this->faker->word,
            'original_name' => $this->faker->word . '.pdf',
            'mime' => 'application/pdf',
            'size' => $this->faker->numberBetween(1000, 5000),
            'path' => 'fake_files/' . $this->faker->word . '.pdf',
            'extension' => 'pdf',
            'user_id' => $this->faker->numberBetween(1, 10), // Adjust according to your user_id generation logic
        ];
    }
}
