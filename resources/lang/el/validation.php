<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted'             => 'Το: χαρακτηριστικό πρέπει να γίνει δεκτό.',
    'active_url'           => 'Η: το χαρακτηριστικό δεν έχει έγκυρη διεύθυνση URL.',
    'after'                => 'Η ιδιότητα: πρέπει να είναι ημερομηνία μετά την ημερομηνία.',
    'after_or_equal'       => 'Η ιδιότητα: πρέπει να είναι ημερομηνία μετά ή ίσο με: ημερομηνία.',
    'alpha'                => 'Η: το χαρακτηριστικό μπορεί να περιέχει μόνο γράμματα.',
    'alpha_dash'           => 'Το χαρακτηριστικό:: μπορεί να περιέχει μόνο γράμματα, αριθμούς και παύλες.',
    'alpha_num'            => 'Το χαρακτηριστικό:: μπορεί να περιέχει μόνο γράμματα και αριθμούς.',
    'array'                => 'Το χαρακτηριστικό πρέπει να είναι ένας πίνακας.',
    'before'               => 'Η ιδιότητα: πρέπει να είναι μια ημερομηνία πριν: η ημερομηνία.',
    'before_or_equal'      => 'Η ιδιότητα: πρέπει να είναι ημερομηνία πριν ή ίσο με: ημερομηνία.',
    'between'              => [
        'numeric' => 'Το χαρακτηριστικό πρέπει να είναι μεταξύ: min - :max.',
        'file'    => 'Το χαρακτηριστικό πρέπει να είναι μεταξύ: min και: max kilobytes.',
        'string'  => 'Η ιδιότητα: πρέπει να είναι μεταξύ: min και max χαρακτήρες.',
        'array'   => 'Η ιδιότητα: πρέπει να έχει μεταξύ: min και max στοιχεία.',
    ],
    'boolean'              => 'Το πεδίο ιδιοτήτων πρέπει να είναι αληθές ή ψευδές.',
    'confirmed'            => 'Η επιβεβαίωση του χαρακτηριστικού δεν αντιστοιχεί.',
    'date'                 => 'Το χαρακτηριστικό: δεν είναι έγκυρη.',
    'date_format'          => 'Το χαρακτηριστικό: δεν αντιστοιχεί στη μορφή: format.',
    'different'            => 'Το χαρακτηριστικό: και: άλλα πρέπει να είναι διαφορετικά.',
    'digits'               => 'Το χαρακτηριστικό: πρέπει να είναι: ψηφία ψηφία.',
    'digits_between'       => 'Το χαρακτηριστικό: πρέπει να είναι μεταξύ: min και max.',
    'dimensions'           => 'Το χαρακτηριστικό:: έχει μη έγκυρες διαστάσεις εικόνας.',
    'distinct'             => 'Το πεδίο ιδιοτήτων: έχει διπλή τιμή.',
    'email'                => 'Το χαρακτηριστικό: πρέπει να είναι έγκυρη διεύθυνση ηλεκτρονικού ταχυδρομείου.',
    'exists'               => 'Το επιλεγμένο: χαρακτηριστικό δεν είναι έγκυρο.',
    'file'                 => 'Το χαρακτηριστικό πρέπει να είναι ένα αρχείο.',
    'filled'               => 'Το πεδίο ιδιοτήτων πρέπει να έχει τιμή.',
    'image'                => 'Το: χαρακτηριστικό πρέπει να είναι μια εικόνα.',
    'import_field_empty'    => 'The value for :fieldname cannot be null.',
    'in'                   => 'Το χαρακτηριστικό επιλεγμένο: δεν είναι έγκυρο.',
    'in_array'             => 'Το πεδίο ιδιοτήτων: δεν υπάρχει σε: άλλο.',
    'integer'              => 'Το χαρακτηριστικό: πρέπει να είναι ένας ακέραιος αριθμός.',
    'ip'                   => 'Το χαρακτηριστικό: πρέπει να είναι μια έγκυρη διεύθυνση IP.',
    'ipv4'                 => 'Το χαρακτηριστικό: πρέπει να είναι μια έγκυρη διεύθυνση IPv4.',
    'ipv6'                 => 'Το χαρακτηριστικό: πρέπει να είναι μια έγκυρη διεύθυνση IPv6.',
    'is_unique_department' => 'The :attribute must be unique to this Company Location',
    'json'                 => 'Το χαρακτηριστικό: πρέπει να είναι μια έγκυρη συμβολοσειρά JSON.',
    'max'                  => [
        'numeric' => 'Η ιδιότητα: δεν μπορεί να είναι μεγαλύτερη από: max.',
        'file'    => 'Η ιδιότητα: δεν μπορεί να είναι μεγαλύτερη από: μέγιστα kilobyte.',
        'string'  => 'Το χαρακτηριστικό:: δεν μπορεί να είναι μεγαλύτερο από: max χαρακτήρες.',
        'array'   => 'Το χαρακτηριστικό:: δεν μπορεί να έχει περισσότερα από: max στοιχεία.',
    ],
    'mimes'                => 'Το χαρακτηριστικό: πρέπει να είναι ένα αρχείο τύπου:: τιμές.',
    'mimetypes'            => 'Το χαρακτηριστικό: πρέπει να είναι ένα αρχείο τύπου:: τιμές.',
    'min'                  => [
        'numeric' => 'Η ιδιότητα: πρέπει να είναι τουλάχιστον: min.',
        'file'    => 'Το χαρακτηριστικό πρέπει να είναι τουλάχιστον: min kilobytes.',
        'string'  => 'Το χαρακτηριστικό: πρέπει να είναι τουλάχιστον: min χαρακτήρες.',
        'array'   => 'Το χαρακτηριστικό: πρέπει να έχει τουλάχιστον: λεπτά στοιχεία.',
    ],
    'starts_with'          => 'The :attribute must start with one of the following: :values.',
    'ends_with'            => 'The :attribute must end with one of the following: :values.',

    'not_in'               => 'Το επιλεγμένο: χαρακτηριστικό δεν είναι έγκυρο.',
    'numeric'              => 'Το χαρακτηριστικό πρέπει να είναι ένας αριθμός.',
    'present'              => 'Πρέπει να υπάρχει το πεδίο ιδιοτήτων: attribute.',
    'valid_regex'          => 'Μη έγκυρη συμβολοσειρά χαρακτήρων ελέγχου πρότυπου. ',
    'regex'                => 'Η μορφή του χαρακτηριστικού είναι μη έγκυρη.',
    'required'             => 'Το πεδίο ιδιοτήτων: απαιτείται.',
    'required_if'          => 'Το πεδίο ιδιοτήτων: απαιτείται όταν: το άλλο είναι: τιμή.',
    'required_unless'      => 'Το πεδίο ιδιοτήτων: απαιτείται εκτός εάν: το άλλο είναι σε: τιμές.',
    'required_with'        => 'Το πεδίο ιδιοτήτων: απαιτείται όταν υπάρχουν: τιμές.',
    'required_with_all'    => 'Το πεδίο ιδιοτήτων: απαιτείται όταν υπάρχουν: τιμές.',
    'required_without'     => 'Το πεδίο ιδιοτήτων: απαιτείται όταν: δεν υπάρχουν τιμές.',
    'required_without_all' => 'Το πεδίο ιδιοτήτων είναι απαραίτητο όταν δεν υπάρχουν καμία από τις τιμές.',
    'same'                 => 'Το χαρακτηριστικό: και: άλλα πρέπει να ταιριάζουν.',
    'size'                 => [
        'numeric' => 'Το χαρακτηριστικό: πρέπει να είναι: μέγεθος.',
        'file'    => 'Το χαρακτηριστικό: πρέπει να είναι: kilobytes μεγέθους.',
        'string'  => 'Το χαρακτηριστικό: πρέπει να είναι: χαρακτήρες μεγέθους.',
        'array'   => 'Το χαρακτηριστικό: πρέπει να περιέχει: στοιχεία μεγέθους.',
    ],
    'string'               => 'Το χαρακτηριστικό πρέπει να είναι μια συμβολοσειρά.',
    'timezone'             => 'Το χαρακτηριστικό: πρέπει να είναι μια έγκυρη ζώνη.',
    'two_column_unique_undeleted' => 'The :attribute must be unique across :table1 and :table2. ',
    'unique'               => 'Το χαρακτηριστικό: έχει ήδη ληφθεί.',
    'uploaded'             => 'Το χαρακτηριστικό:: απέτυχε να μεταφορτωθεί.',
    'url'                  => 'Η μορφή του χαρακτηριστικού είναι μη έγκυρη.',
    'unique_undeleted'     => 'Το :χαρακτηριστικό πρέπει να είναι μοναδικό.',
    'non_circular'         => 'The :attribute must not create a circular reference.',
    'not_array'            => 'The :attribute field cannot be an array.',
    'unique_serial'        => 'The :attribute must be unique.',
    'disallow_same_pwd_as_user_fields' => 'Password cannot be the same as the username.',
    'letters'              => 'Password must contain at least one letter.',
    'numbers'              => 'Password must contain at least one number.',
    'case_diff'            => 'Password must use mixed case.',
    'symbols'              => 'Password must contain symbols.',
    'gte'                  => [
        'numeric'          => 'Value cannot be negative'
    ],


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'alpha_space' => 'Το πεδίο ιδιοτήτων: περιέχει ένα χαρακτήρα που δεν επιτρέπεται.',
        'email_array'      => 'Μία ή περισσότερες διευθύνσεις ηλεκτρονικού ταχυδρομείου δεν είναι έγκυρες.',
        'hashed_pass'      => 'Ο τρέχων κωδικός πρόσβασης είναι εσφαλμένος',
        'dumbpwd'          => 'Αυτός ο κωδικός πρόσβασης είναι πολύ συνηθισμένος.',
        'statuslabel_type' => 'Πρέπει να επιλέξετε έναν έγκυρο τύπο ετικέτας κατάστασης',

        // date_format validation with slightly less stupid messages. It duplicates a lot, but it gets the job done :(
        // We use this because the default error message for date_format is reflects php Y-m-d, which non-PHP
        // people won't know how to format. 
        'purchase_date.date_format'     => 'The :attribute must be a valid date in YYYY-MM-DD format',
        'last_audit_date.date_format'   =>  'The :attribute must be a valid date in YYYY-MM-DD hh:mm:ss format',
        'expiration_date.date_format'   =>  'The :attribute must be a valid date in YYYY-MM-DD format',
        'termination_date.date_format'  =>  'The :attribute must be a valid date in YYYY-MM-DD format',
        'expected_checkin.date_format'  =>  'The :attribute must be a valid date in YYYY-MM-DD format',
        'start_date.date_format'        =>  'The :attribute must be a valid date in YYYY-MM-DD format',
        'end_date.date_format'          =>  'The :attribute must be a valid date in YYYY-MM-DD format',

    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
