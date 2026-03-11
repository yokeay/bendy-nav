import postgres from "postgres";
import { getDatabaseUrl } from "@/lib/app-config";

type SqlClient = postgres.Sql<{}>;

declare global {
  // eslint-disable-next-line no-var
  var __mtabSql: SqlClient | undefined;
}

function createClient(): SqlClient {
  return postgres(getDatabaseUrl(), {
    max: 10,
    idle_timeout: 30,
    connect_timeout: 30,
    prepare: false
  });
}

const sql: SqlClient = global.__mtabSql ?? createClient();

if (process.env.NODE_ENV !== "production") {
  global.__mtabSql = sql;
}

export default sql;
