# Front End

This project is the front-end application for the same API which is available [here](../backend).

## Libraries

- NextJS
- Material UI

## 🏃‍♂️ Running

First of all, you make sure that you have Docker installed on your machine and then you need to run this command on root's repository:

```bash
docker compose up -d
```

Then, you just need to connect into your docker machine by running:

```bash
docker exec -it micro-videos-app bash
```

Inside your docker, you just run:

```bash
cd frontend
```

Finally, you install project's dependencies:

```bash
yarn
```

### Development

To run the application in development mode:

```bash
yarn dev
```

and then you can access it from this URL:

http://localhost:3000/
