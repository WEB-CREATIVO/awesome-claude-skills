FROM python:3.12-slim
WORKDIR /srv
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt
COPY . .
# SQLite en un volumen: -v bot-data:/data
ENV DB_PATH=/data/bot.db
CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "8000"]
