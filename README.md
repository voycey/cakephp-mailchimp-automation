# CakePHP Mailchimp Automation
A 2.x shell to easily add a user to an automation queue using API 3.0 in mailchimp.

In order to add a person to an automation queue in Mailchimp there are a few steps that need to be followed to get them registered correctly:

1. Register the person to a list
1. Add the person to an automation queue with:
  1. Automation ID
  1. Email ID

When you create an automation queue in mailchimp (API Triggered) you will see the trigger instructions similar to this:

**API Call to: /automations/5d249e10d6/emails/0bbd43044d/queue**

This is broken down as:

API Call to: /automations/**automation_id**/emails/**email_id**/queue

# Usage
As with most API operations this should be used with a deferred execution method, I use CakeResque as a simple work queue but you can use whatever you want:

The data sent to this shell needs to be in the format:

```php
$person = [
  "email_address" => $this->request->data['User']['email'],
  "status" => "subscribed",
  "merge_fields" => [
    "FNAME" => $this->request->data['User']['first_name'],
    "LNAME" => $this->request->data['User']['last_name']
  ]
];
```
You can pass whatever other merge_fields you want above as well and they will be inserted into mailchimp alongside the users record.

## Using CakeResque (or any other work queue):

```php
switch (strtolower($this->request->data['User']['lead_source'])) {
  case "certification":
    CakeResque::enqueue("mailchimp", "MailchimpShell", array("automate", "certification", $person), true);
    break;
  case "enquiry-form":
    break; //ignore enquiry form
  default:
    CakeResque::enqueue("mailchimp", "MailchimpShell", array("automate", "hero-image", $person), true);
    break; 
}
```

## Using without a work queue:

```php
App::uses('MailchimpShell', 'Console/Command');
$this->Mailchimp = new MailchimpShell();
$this->Mailchimp->automate("hero-image", $person);
