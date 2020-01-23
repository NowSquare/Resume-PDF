<?php

use Illuminate\Database\Seeder;
use Platform\Controllers\Core;

use Faker\Factory as Faker;
use Illuminate\Support\Arr;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      Eloquent::unguard();

      $user = new \App\User;

      $user->role = 1;
      $user->name = 'System Admin';
      $user->email = 'admin@example.com';
      $user->password = bcrypt('welcome123');
      $user->language = config('default.language');
      $user->locale = config('default.locale');
      $user->timezone = config('default.timezone');
      $user->currency = config('default.currency');

      $user->save();

      /**
       * -----------------------------------------------------------------
       * Demo seeds
       * -----------------------------------------------------------------
       */

      $user_count = 20;

      if (env('APP_DEMO', false)) {
        $faker = Faker::create();

        $user = new \App\User;
        $user->role = 2;
        $user->name = 'Hannah McLaughlin';
        $user->email = 'user@example.com';
        $user->password = bcrypt('welcome123');
        $user->job_title = 'Full Stack Developer';
        $user->bio = 'Entrepreneur and Full Stack Web Developer.';
        $user->contact_email = 'resume@example.com';
        $user->date_of_birth = '1978-04-22';
        $user->contact_phone = $faker->tollFreePhoneNumber;
        $user->website = 'https://example.com';
        $user->languages = 'English (natively), German, French';
        $user->linkedin = 'https://www.linkedin.com/company/';
        $user->address1 = $faker->streetAddress;
        $user->address2 = $faker->postcode . ' ' . $faker->stateAbbr;
        $user->address3 = $faker->country;
        $user->language = config('default.language');
        $user->locale = config('default.locale');
        $user->timezone = config('default.timezone');
        $user->currency = config('default.currency');
        $user->created_by = 1;

        $user->save();

        $avatar = base_path() . '/database/seeds/media/avatar.jpg';

        $user
        ->addMedia($avatar)
        ->preservingOriginal()
        ->sanitizingFileName(function($fileName) {
          return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
        })
        ->toMediaCollection('avatar', 'media');

        // Add tags
        $tags = ['UI/UX', 'PHP', 'Laravel', 'Vue', 'SQL', 'NoSQL', 'JavaScript', 'TypeScript', 'HTML', 'SEO', 'CSS', 'SASS', 'LESS', 'Bootstrap', 'Git', 'Apache', 'Unix', 'Photoshop', 'Illustrator', 'Marketing'];

        $syncTags = [];
        foreach ($tags as $tag) {
          $t = new \Platform\Models\Tag;
          $t->name = $tag;
          $t->created_by = 2;
          $t->save();
          array_push($syncTags, $t->id);
        }

        $user->tags()->sync($syncTags);

        // Add experience
        $experiences = [];

        $years_ago = 22;
        $started_at = $faker->dateTimeBetween($startDate = '-' . $years_ago . ' years', $startDate = '-' . ($years_ago - 1) . ' years');
        $years = 3;
        $ended_at = $faker->dateTimeBetween($startDate = '-' . ($years_ago - $years) . ' years', $startDate = '-' . ($years_ago - $years - 1) . ' years');
        $years_ago = $years_ago - $years;

        $experiences[] = [
          'type' => 'education',
          'name' => $faker->company,
          'location' => $faker->city . ', ' . $faker->stateabbr,
          'description' => '<p>' . implode('</p><p>', $faker->paragraphs($nb = 1, $asText = false)) . '</p>',
          'started_at' => $started_at,
          'ended_at' => $ended_at
        ];

        $started_at = $ended_at;
        $years = 4;
        $years_ago = $years_ago - $years;
        $ended_at = $faker->dateTimeBetween($startDate = '-' . ($years_ago) . ' years', $startDate = '-' . ($years_ago - 1) . ' years');

        $experiences[] = [
          'type' => 'education',
          'name' => $faker->company,
          'location' => $faker->city . ', ' . $faker->stateabbr,
          'description' => '<p>' . implode('</p><p>', $faker->paragraphs($nb = 1, $asText = false)) . '</p>',
          'started_at' => $started_at,
          'ended_at' => $ended_at
        ];

        $started_at = $ended_at;
        $years = 2;
        $years_ago = $years_ago - $years;
        $ended_at = $faker->dateTimeBetween($startDate = '-' . ($years_ago) . ' years', $startDate = '-' . ($years_ago - 1) . ' years');

        $experiences[] = [
          'type' => 'work',
          'name' => $faker->company,
          'location' => $faker->city . ', ' . $faker->stateabbr,
          'description' => '<p>' . implode('</p><p>', $faker->paragraphs($nb = 1, $asText = false)) . '</p>',
          'started_at' => $started_at,
          'ended_at' => $ended_at
        ];

        $started_at = $ended_at;
        $years = 4;
        $years_ago = $years_ago - $years;
        $ended_at = $faker->dateTimeBetween($startDate = '-' . ($years_ago) . ' years', $startDate = '-' . ($years_ago - 1) . ' years');

        $experiences[] = [
          'type' => 'work',
          'name' => $faker->company,
          'location' => $faker->city . ', ' . $faker->stateabbr,
          'description' => '<p>' . implode('</p><p>', $faker->paragraphs($nb = 1, $asText = false)) . '</p>',
          'started_at' => $started_at,
          'ended_at' => $ended_at
        ];

        $started_at = $ended_at;
        $years = 5;
        $years_ago = $years_ago - $years;
        $ended_at = $faker->dateTimeBetween($startDate = '-' . ($years_ago) . ' years', $startDate = '-' . ($years_ago - 1) . ' years');

        $experiences[] = [
          'type' => 'work',
          'name' => $faker->company,
          'location' => $faker->city . ', ' . $faker->stateabbr,
          'description' => '<p>' . implode('</p><p>', $faker->paragraphs($nb = 1, $asText = false)) . '</p>',
          'started_at' => $started_at,
          'ended_at' => $ended_at
        ];

        $started_at = $ended_at;
        $years = 3;
        $years_ago = $years_ago - $years;
        $ended_at = $faker->dateTimeBetween($startDate = '-' . ($years_ago) . ' years', $startDate = '-' . ($years_ago - 1) . ' years');

        $experiences[] = [
          'type' => 'work',
          'name' => $faker->company,
          'location' => $faker->city . ', ' . $faker->stateabbr,
          'description' => '<p>' . implode('</p><p>', $faker->paragraphs($nb = 1, $asText = false)) . '</p>',
          'started_at' => $started_at,
          'ended_at' => null
        ];

        foreach ($experiences as $experience) {
          $e = new \Platform\Models\ResumeExperience;
          $e->type = $experience['type'];
          $e->name = $experience['name'];
          $e->location = $experience['location'];
          $e->description = $experience['description'];
          $e->started_at = $experience['started_at'];
          $e->ended_at = $experience['ended_at'];
          $e->created_by = 2;
          $e->save();
        }

        // Add projects
        $projects = [];

        $projects[] = [
          'title' => $faker->catchPhrase,
          'description' => '<p>' . implode('</p><p>', $faker->paragraphs($nb = 1, $asText = false)) . '</p>',
          'started_at' => $faker->dateTimeBetween($startDate = '-12 years', $startDate = '-11 years')
        ];

        $projects[] = [
          'title' => $faker->catchPhrase,
          'description' => '<p>' . implode('</p><p>', $faker->paragraphs($nb = 1, $asText = false)) . '</p>',
          'started_at' => $faker->dateTimeBetween($startDate = '-10 years', $startDate = '-9 years')
        ];

        $projects[] = [
          'title' => $faker->catchPhrase,
          'description' => '<p>' . implode('</p><p>', $faker->paragraphs($nb = 1, $asText = false)) . '</p>',
          'started_at' => $faker->dateTimeBetween($startDate = '-8 years', $startDate = '-7 years')
        ];

        $projects[] = [
          'title' => $faker->catchPhrase,
          'description' => '<p>' . implode('</p><p>', $faker->paragraphs($nb = 1, $asText = false)) . '</p>',
          'started_at' => $faker->dateTimeBetween($startDate = '-6 years', $startDate = '-5 years')
        ];

        $projects[] = [
          'title' => $faker->catchPhrase,
          'description' => '<p>' . implode('</p><p>', $faker->paragraphs($nb = 1, $asText = false)) . '</p>',
          'started_at' => $faker->dateTimeBetween($startDate = '-4 years', $startDate = '-3 years')
        ];

        $projects[] = [
          'title' => $faker->catchPhrase,
          'description' => '<p>' . implode('</p><p>', $faker->paragraphs($nb = 1, $asText = false)) . '</p>',
          'started_at' => $faker->dateTimeBetween($startDate = '-2 years', $startDate = '-1 years')
        ];

        foreach ($projects as $index => $project) {
          $p = new \Platform\Models\ResumeProject;
          $p->title = $project['title'];
          $p->description = $project['description'];
          $p->started_at = $project['started_at'];
          $p->created_by = 2;
          $p->save();

          // Tags
          $t = Arr::random(range(1, count($tags)), mt_rand(3, 5));
          $p->tags()->sync($t);

          // Image
          $image = base_path() . '/database/seeds/media/project' . ($index + 1) . '.jpg';

          if (\File::exists($image)) {
            $p
            ->addMedia($image)
            ->preservingOriginal()
            ->sanitizingFileName(function($fileName) {
              return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
            })
            ->toMediaCollection('image', 'media');
          }
        }

        $men = 1;
        $women = 1;

        foreach (range(1, $user_count) as $index) {
          $gender = (mt_rand(0, 1) == 1) ? 'male' : 'female';
          if ($gender == 'male') {
            $firstName = $faker->firstNameMale;
            $avatar = base_path() . '/database/seeds/avatars/men/' . $men . '.jpg';
            $men++;
          } else {
            $firstName = $faker->firstNameFemale;
            $avatar = base_path() . '/database/seeds/avatars/women/' . $women . '.jpg';
            $women++;
          }
          $lastName = $faker->lastName;
          $email = Str::slug(substr($firstName, 0, 1)) . '.' . Str::slug($lastName, '_') . '@' . $faker->domainName;

          $created_at = $faker->dateTimeBetween($startDate = '-1 months', $endDate = 'now');
          $updated_at = $faker->dateTimeBetween($startDate = $created_at, $endDate = 'now');

          $user = new \App\User;
          $user->role = 2;
          $user->name = $firstName . ' ' . $lastName;
          $user->job_title = $faker->jobTitle;
          $user->email = $email;
          $user->contact_email = $email;
          $user->password = bcrypt('welcome123');
          $user->bio = $faker->catchPhrase;
          $user->date_of_birth = $faker->dateTimeBetween($startDate = '-39 years', $startDate = '-22 years');
          $user->contact_phone = $faker->tollFreePhoneNumber;
          $user->website = 'https://example.com';
          $user->languages = 'English (natively), German, French';
          $user->linkedin = 'https://www.linkedin.com/company/';
          $user->address1 = $faker->streetAddress;
          $user->address2 = $faker->postcode . ' ' . $faker->stateAbbr;
          $user->address3 = $faker->country;
          $user->language = config('default.language');
          $user->locale = config('default.locale');
          $user->timezone = config('default.timezone');
          $user->currency = config('default.currency');
          $user->logins = mt_rand(1, 12);
          $user->last_login = $updated_at;
          $user->last_ip_address = $faker->ipv4;
          $user->created_by = 1;
          $user->created_at = $created_at;
          $user->updated_by = 1;
          $user->updated_at = $updated_at;

          $user->save();

          $user
          ->addMedia($avatar)
          ->preservingOriginal()
          ->sanitizingFileName(function($fileName) {
            return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
          })
          ->toMediaCollection('avatar', 'media');

          // Add tags
          $tags = [];
          for ($i = 0; $i < mt_rand(3, 9); $i++) {
            $tags[] = ucfirst($faker->word);
          }

          $syncTags = [];
          foreach ($tags as $tag) {
            $t = new \Platform\Models\Tag;
            $t->name = $tag;
            $t->created_by = $user->id;
            $t->save();
            array_push($syncTags, $t->id);
          }

          $user->tags()->sync($syncTags);

          // Add experience
          $experiences = [];

          $years_ago = 16;
          $started_at = $faker->dateTimeBetween($startDate = '-' . $years_ago . ' years', $startDate = '-' . ($years_ago - 1) . ' years');
          $years = 3;
          $ended_at = $faker->dateTimeBetween($startDate = '-' . ($years_ago - $years) . ' years', $startDate = '-' . ($years_ago - $years - 1) . ' years');
          $years_ago = $years_ago - $years;

          $experiences[] = [
            'type' => 'education',
            'name' => $faker->company,
            'location' => $faker->city . ', ' . $faker->stateabbr,
            'description' => '<p>' . implode('</p><p>', $faker->paragraphs($nb = 1, $asText = false)) . '</p>',
            'started_at' => $started_at,
            'ended_at' => $ended_at
          ];

          $started_at = $ended_at;
          $years = 2;
          $years_ago = $years_ago - $years;
          $ended_at = $faker->dateTimeBetween($startDate = '-' . ($years_ago) . ' years', $startDate = '-' . ($years_ago - 1) . ' years');

          $experiences[] = [
            'type' => 'education',
            'name' => $faker->company,
            'location' => $faker->city . ', ' . $faker->stateabbr,
            'description' => '<p>' . implode('</p><p>', $faker->paragraphs($nb = 1, $asText = false)) . '</p>',
            'started_at' => $started_at,
            'ended_at' => $ended_at
          ];

          $started_at = $ended_at;
          $years = 3;
          $years_ago = $years_ago - $years;
          $ended_at = $faker->dateTimeBetween($startDate = '-' . ($years_ago) . ' years', $startDate = '-' . ($years_ago - 1) . ' years');

          $experiences[] = [
            'type' => 'work',
            'name' => $faker->company,
            'location' => $faker->city . ', ' . $faker->stateabbr,
            'description' => '<p>' . implode('</p><p>', $faker->paragraphs($nb = 1, $asText = false)) . '</p>',
            'started_at' => $started_at,
            'ended_at' => $ended_at
          ];

          $started_at = $ended_at;
          $years = 4;
          $years_ago = $years_ago - $years;
          $ended_at = $faker->dateTimeBetween($startDate = '-' . ($years_ago) . ' years', $startDate = '-' . ($years_ago - 1) . ' years');

          $experiences[] = [
            'type' => 'work',
            'name' => $faker->company,
            'location' => $faker->city . ', ' . $faker->stateabbr,
            'description' => '<p>' . implode('</p><p>', $faker->paragraphs($nb = 1, $asText = false)) . '</p>',
            'started_at' => $started_at,
            'ended_at' => $ended_at
          ];

          foreach ($experiences as $experience) {
            $e = new \Platform\Models\ResumeExperience;
            $e->type = $experience['type'];
            $e->name = $experience['name'];
            $e->location = $experience['location'];
            $e->description = $experience['description'];
            $e->started_at = $experience['started_at'];
            $e->ended_at = $experience['ended_at'];
            $e->created_by = $user->id;
            $e->save();
          }
        }
      }

      Eloquent::reguard();
    }
}
