export const DEFAULT_DATABASE_URL =
  "postgresql://neondb_owner:npg_LvTB3UknZyC0@ep-old-haze-a1b9r6vf-pooler.ap-southeast-1.aws.neon.tech/neondb?sslmode=require&channel_binding=require";

export function getDatabaseUrl(): string {
  const value = process.env.DATABASE_URL?.trim();
  if (value) {
    return value;
  }
  return DEFAULT_DATABASE_URL;
}
