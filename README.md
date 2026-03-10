# 笨迪导航（bendy-nav）

笨迪导航是一个可自部署的导航页/新标签页项目。  
当前版本后端技术栈为 **Next.js + PostgreSQL**，兼容原有前端资源与历史接口路径。

## 技术栈

- Frontend: 现有静态构建资源（`public/dist`）
- Backend: Next.js App Router（Node.js Runtime）
- Database: PostgreSQL（默认支持 Neon）
- Image: `sharp`
- Mail: `nodemailer`

## 快速开始

1. 安装依赖

```bash
npm install
```

2. 初始化数据库

```bash
npm run db:init
```

3. 启动开发环境

```bash
npm run dev
```

默认访问：`http://127.0.0.1:3000`

## 生产运行

```bash
npm run build
npm run start
```

## 环境变量

- `DATABASE_URL`: PostgreSQL 连接串  
  若未配置，项目会使用代码中的默认测试连接。

## 项目结构（关键）

- `app/`：Next.js 路由入口
- `src/legacy/handler.ts`：旧接口兼容分发与核心业务处理
- `scripts/sql/schema.sql`：PostgreSQL 表结构
- `scripts/sql/seed.sql`：初始化种子数据
- `docs/SERVICE_TERMS.html`：默认服务条款内容

## 法律与许可说明

本项目“笨迪导航（bendy-nav）”包含基于 **mtab** 开源项目的二次开发内容。  
分发与使用时请遵守相关开源许可要求，尤其是 MIT 许可下的版权与许可保留义务。

- 服务条款：`/privacy` 页面（默认内容来自 `docs/SERVICE_TERMS.html`）
- 原许可文件：`LICENSE.txt`

