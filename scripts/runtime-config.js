const fs = require("node:fs");
const path = require("node:path");

const CONFIG_BASENAME = "app.config.json";
const CONFIG_ENV_PATH = process.env.APP_CONFIG_PATH?.trim();

function resolveConfigPath() {
  if (CONFIG_ENV_PATH) {
    return CONFIG_ENV_PATH;
  }

  let current = process.cwd();
  for (let i = 0; i < 4; i += 1) {
    const candidate = path.join(current, CONFIG_BASENAME);
    if (fs.existsSync(candidate)) {
      return candidate;
    }
    const parent = path.dirname(current);
    if (parent === current) {
      break;
    }
    current = parent;
  }

  return path.join(process.cwd(), CONFIG_BASENAME);
}

function loadConfig() {
  const configPath = resolveConfigPath();
  let config = {};

  try {
    const content = fs.readFileSync(configPath, "utf8");
    const normalized = content.replace(/^\uFEFF/, "");
    config = JSON.parse(normalized) ?? {};
  } catch (error) {
    console.warn(`Missing or invalid config file: ${configPath}`);
  }

  return { config, configPath };
}

function getConfiguredPort(config) {
  const port = Number(config?.server?.port);
  if (Number.isFinite(port) && port > 0) {
    return port;
  }
  return undefined;
}

module.exports = {
  loadConfig,
  getConfiguredPort
};
