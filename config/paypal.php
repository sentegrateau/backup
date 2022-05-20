<?php
return array(
    /** set your paypal credential **/
    'client_id' => 'Afve8VnyY0x8OQwb-tqFo6rwC3XzZkh_iCEz_JJGCXC68RxttJBXPAHbEdEttO3x3RDSXE2uOntx-NVg',
    'secret' => 'EKam_4-h9O3abSnhfpJnSwj7GvqvUC_8tAeFpOALfcyrSGrVRYGJKLfOCV_ewGJ52E8kgplOlmLNZccF',
    /**
     * SDK configuration
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'sandbox',
        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 1000,
        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,
        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',
        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    )
);