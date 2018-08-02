## Info

This is a CCD Schema generator plugin built on top of [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate). It simplifies the process of registration of custom post types, taxonomies and terms and adds some useful reusable features to work with internal WP entities.

## How it works

The main functionality of the plugin is organized within 2 main files:

`includes/class-ccd-schema-generator` - the list of declarations, located inside of `define_admin_hooks` function. Consists of 2 parts: *registrations* and *additional features*.

`admin/class-ccd-schema-generator-admin` - the list of corresponding functions. Consists of 2 types of functions: *immutable* and *mutable*

**Registrations:**

The *mutable* function `call_registration` is hooked to `init` internal WP hook.

The *immutable* functions `register_post_types`, `register_taxonomies`, `register_terms` are hooked to corresponding custom hooks we would call using `do_action` inside `call_registration` function.

**Additional features:**

The *mutable* functions `term_adding_prevent` `term_removing_prevent`, `set_default_terms` are hooked to corresponding internal WP hooks according to additional feature needs. These features can be activated using `add_theme_supports()` inside of main theme.

Example of usage:
`add_theme_support('term_adding_prevent')`

**Immutable functions:**

`register_post_types` is an internal framework core function developed to register custom post types. It receives 1 argument, the array of CPTs.

Example of usage:
`do_action( 'custom_cpt_hook', $cpt_args )`

`register_taxonomies` is an internal framework core function developed to register custom taxonomies. It receives 1 argument, the array of taxonomies.

Example of usage:
`do_action( 'custom_taxonomies_hook', $tax_args )`

`register_terms` is an internal framework core function developed to register custom terms. It receives 1 argument, the array of terms.

Example of usage:
`do_action( 'custom_terms_hook', $term_args )`

**Mutable functions:**

`call_registration` - inside of this function user can fulfill the corresponding arrays of CPTs, taxonomies and terms and call corresponding hooks using `do_action`.

`term_adding_prevent`- inside of this function in *$taxonomies* array user can specify the list of taxonomies he doesn't allow to add term into.

`term_removing_prevent` - inside of this function in *$taxonomies* array user can specify the list of taxonomies whose terms he doesn't allow to remove.

`set_default_terms` - inside of this function in *$defaults* associative array user can specify the key->value list of taxonomies->array of terms which would be assigned by default on post save.

## The bottom line

In order to register custom post types, taxonomies and terms, user shoud:

1. Take a look around `register_post_types`, `register_taxonomies`, `register_terms` functions inside `admin/class-ccd-schema-generator-admin` file in order to see the list of arguments allowed to override. ( *$defaults* ).
2. Find `call_registration` function inside `admin/class-ccd-schema-generator-admin` file. 
3. According to provided examples, fulfill corresponding *cpt_args*, *tax_args*, *term_args* arrays overriding variables due to project needs.

In order to enable some additional feature (for example, to prevent adding terms to some taxonomies), user should:

1. Enable corresponding feature inside of theme framework core:
`add_theme_support('term_adding_prevent')`
2. Find `term_adding_prevent` function inside `admin/class-ccd-schema-generator-admin` file.
3. Fulfill $taxonomies array with the list of taxonomies user won't be able to add terms into.


