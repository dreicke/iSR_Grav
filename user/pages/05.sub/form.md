---
title: Subscribe
form:
    name: subscribers
    fields:
        -
            name: name
            label: Name
            placeholder: 'Enter your name'
            autofocus: 'on'
            autocomplete: 'on'
            type: text
            validate:
                required: true
        -
            name: email
            label: Email
            placeholder: 'Enter your email address'
            type: email
            validate:
                required: true
    buttons:
        -
            type: submit
            value: SUBMIT
        -
            type: reset
            value: RESET
    process:
        -
            email:
                from: '{{ config.plugins.email.from }}'
                to: ['{{ config.plugins.email.from }}', '{{ form.value.email }}']
                subject: 'Thank you for your interest!'
                body: 'We will notify you upon publication of our first issue. Here is the information we received:<br>{% include ''forms/data.html.twig'' %}'
        -
            save:
                fileprefix: feedback-
                dateformat: Ymd-His-u
                extension: txt
                body: '{% include ''forms/data.txt.twig'' %}'
        -
            message: 'Thank you for your information!'
        -
            display: thankyou
---

<h1 id="frontpage">Stay in Touch!</h1>
<p class="introtext"> We'll send you an email when the next issue is ready :)</p>