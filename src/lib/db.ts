import postgres from "postgres";
import { getDatabaseUrl } from "@/lib/env";

declare global {
  // eslint-disable-next-line no-var
  var __mtabSql: ReturnType<typeof postgres> | undefined;
}

function createClient() {
  return postgres(getDatabaseUrl(), {
    max: 10,
    idle_timeout: 30,
    connect_timeout: 30,
    prepare: false
  });
}

const sql = global.__mtabSql ?? createClient();

if (process.env.NODE_ENV !== "production") {
  global.__mtabSql = sql;
}

export default sql;
