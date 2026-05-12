// ============================================================
// Supabase Client Configuration
// Replace these values with your Supabase project credentials:
//   https://app.supabase.com/project/_/settings/api
// ============================================================

const SUPABASE_URL = 'https://vsckuagzefptoyamrnag.supabase.co';
const SUPABASE_ANON_KEY = 'sb_publishable_9e9NJjiPZnls1qLkW7aPYw_W1LecdMZ';

const supabase = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
