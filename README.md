# Symfony Bundle for retry policy in Symfony Messenger

This is a Bundle to add the ability to choose the retry policy based on the type of message you are trying to process.

**Note: Messages should ultimately inherit from [Message](https://github.com/PcComponentes/ddd/blob/master/src/Util/Message/Message.php)**.

## Installation

1) Install via [composer](https://getcomposer.org/)

    ```shell script
    composer require pccomponentes/messenger-retry-bundle
    ```

2) Write the bundle configuration file (`config/packages/messenger_retry.yaml`) indicating the retry policies for each
   message class and the action you want to execute in each of them. An example of its content is:

    ```yaml
    messenger_retry:
      - max_retries: 5
        delay_milliseconds: 2000
        multiplier: 2
        max_delay_milliseconds: 2800000
        msg_types:  
          - PcComponentes\Ddd\Application\Command: auto
        # - PcComponentes\Ddd\Application\Command: always_retry
        # - PcComponentes\Ddd\Application\Command: never_retry
    ```

   This file will be modified to add or remove any retry policies needed for the project it is used in.
