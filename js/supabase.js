// ============================================================
// Supabase Client Configuration
// Replace these values with your Supabase project credentials:
//   https://app.supabase.com/project/_/settings/api
// ============================================================

var SUPABASE_URL = 'https://vsckuagzefptoyamrnag.supabase.co';
var SUPABASE_ANON_KEY = 'sb_publishable_9e9NJjiPZnls1qLkW7aPYw_W1LecdMZ';
var supabase;

if (typeof window.supabase !== 'undefined') {
  supabase = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
}
