<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class UploadCleanupPlugin
 * @package Grav\Plugin
 */
class UploadCleanupPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => [
                // Uncomment following line when plugin requires Grav < 1.7
                // ['autoload', 100000],
                ['onPluginsInitialized', 0]
            ],
            'onFormProcessed' => ['onFormProcessed', 0]
        ];
    }

    /**
     * Composer autoload
     *
     * @return ClassLoader
     */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized(): void
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main events we are interested in
        $this->enable([
            // Put your main events here
        ]);
    }

    public function onFormProcessed(Event $event)
    {
        $form = $event['form'];
        $action = $event['action'];

        if ( $action === 'upload' && key_exists( 'cleanup', $event['params'] ) && $event['params']['cleanup'] == true )
        {
            $this->deleteUploadedFiles();
        }
    }

    private function deleteUploadedFiles()
    {
        $uploadDir = 'tmp/uploads';

        if ( ! is_dir( $uploadDir ) )
        {
            return false;
        }

        $files = glob( $uploadDir . '/*' );

        if ( empty( $files ) )
        {
            return; // Directory is already empty
        }

        foreach ( $files as $file )
        {
            if ( is_file( $file ) )
            {
                unlink( $file );
            }
        }
    }
}
