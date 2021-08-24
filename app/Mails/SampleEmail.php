<?php

namespace App\Mails;

use App\Models\FooModel;
use App\Models\BarModel;

class SampleEmail extends TagManagerMailable {

  // Required manual tags in parameter
	const requiredTags = [
        'chat_link'
    ];

  // Required Objects in parameter
    const requiredObjects = [
        FooModel::class,
        BarModel::class
    ];
}
