# Upload Cleanup Plugin

The **Upload Cleanup** Plugin is an extension for [Grav CMS](https://github.com/getgrav/grav). It prevents file uploads in forms from being saved permanently if you only need them as email attachment for example.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/upload-cleanup/upload-cleanup.yaml` to `user/config/plugins/upload-cleanup.yaml` and only edit that copy.

There is only the option to enable/disable it.

```yaml
enabled: true
```

## Usage

In your form configuration set the destination of the uploads you don't want to persist to `tmp/uploads` and add `upload.cleanup: true` to the `process` object. Make sure the upload is noted after other processings.

```yaml
form:
  name: application
  action: "#application"
  classes: form
  fields:

    …

    file:
      label: curriculum vitae
      type: file
      multiple: false
      filesize: 4
      destination: tmp/uploads
      avoid_overwriting: true
      accept:
        - application/pdf
        - application/x-pdf
        - text/plain
        - image/jpeg
        - application/msword
        - application/vnd.openxmlformats-officedocument.wordprocessingml.document
        - application/vnd.oasis.opendocument.text

  …

  process:
    email:
      from: "{{ config.plugins.email.from }}"
      from_name: "{{ form.value.name }}"
      to: "{{ config.details.jobs_email }}"
      reply_to: "{{ form.value.email }}"
      subject: "New Job Application"
      body: "{% include 'email/application.txt.twig' %}"
      content_type: 'text/plain'
      attachments:
        - 'file'
    upload:
      cleanup: true


```

The plugin basically deletes all files inside the `tmp/uploads` when it is called to process.

## Installation

Installing the News plugin can be done in multiple ways: The manual method lets you do so via a zip file, installation via dependecies and the admin method lets you do so via the Admin Plugin.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `upload-cleanup`. You can find these files on [GitHub](https://github.com/bitstarr/grav-plugin-upload-cleanup) or via [GetGrav.org](https://getgrav.org/downloads/plugins).

You should now have all the plugin files under

    /your/site/grav/user/plugins/upload-cleanup
	
> NOTE: This plugin is a modular component for Grav which may require other plugins to operate, please see its [blueprints.yaml-file on GitHub](https://github.com/bitstarr/grav-plugin-upload-cleanup/blob/main/blueprints.yaml).

### Installation as dependency (skeleton)

If you don't know this method already, check out this [example of a dependecies file](https://github.com/bitstarr/sebastianlaube/blob/main/user/.dependencies). It can hold all (external) plugins and themes you require to run your project. When running `bin/grav install` all these will get downloaded and correctly placed automatically.

Add the following to your `.dependecies` file:

```
    upload-cleanup:
        url: https://github.com/bitstarr/grav-plugin-upload-cleanup
        path: user/plugins/upload-cleanup
        branch: main
```

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.
