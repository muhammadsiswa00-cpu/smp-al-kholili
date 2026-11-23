import { createClient, type Client } from '@libsql/client/web';

const url = import.meta.env.TURSO_DATABASE_URL || process.env.TURSO_DATABASE_URL || '';
const authToken = import.meta.env.TURSO_AUTH_TOKEN || process.env.TURSO_AUTH_TOKEN || '';

let tursoClient: Client | null = null;

if (url && authToken) {
  try {
    tursoClient = createClient({
      url,
      authToken,
    });
  } catch (err) {
    console.warn('[Turso] Gagal menghubungkan ke Turso libSQL:', err);
  }
}

export const turso = tursoClient;
export const isTursoConfigured = Boolean(tursoClient);
