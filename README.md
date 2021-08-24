# How to use

```
<?php
$template = new SampleEMail;
$foo = new Foo;
$bar = new Bar;
$user = User::find(1);

$template ->addManualTag('chat_link', "my_custom_link")
          ->addTagObject($foo)
          ->addTagObject($bar);
          ->addTagObject($user);

 Mail::to($user->email)
       ->send($template);
```


# Database : Sample mail_template with tags

```
<p>
    The foo name : {{ foo_firstname}} {{ foo_name }}
</p>
<p>
    The Bar code with the manual tag "chat_link" : <a href='{{ chat_link }}'>{{ bar_code }}</a>
</p>
```



# Can i use tags in subject or text_template ?

Yes, you can. You have nothing to do.
