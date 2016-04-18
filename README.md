# cakephp-mailchimp-automation
A shell to easily add a user to an automation queue using API 3.0 in mailchimp.

In order to add a person to an automation queue in Mailchimp there are a few steps that need to be followed to get them registered correctly:

1. Register the person to a list
1. Add the person to an automation queue with:
  1. Automation ID
  1. Email ID

When you create an automation queue in mailchimp (API Triggered) you will see the trigger instructions similar to this:

**API Call to: /automations/5d249e00d6/emails/0bbd53044d/queue**

This is broken down as:

API Call to: /automations/**automation_id**/emails/**email_id**/queue

# Usage
As with most API operations this should be used with a deferred execution method, I use CakeResque as a simple work queue but you can use whatever you want


