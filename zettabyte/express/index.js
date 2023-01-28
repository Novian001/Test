const express = require("express");
const cors = require("cors");
const mongoose = require("mongoose");

const app = express();
app.use(cors());
app.use(express.json());
mongoose.set("strictQuery", false);

const uri = "mongodb+srv://novian:sarungman@cluster0.yedwx1r.mongodb.net/test";
mongoose.connect(uri, { useNewUrlParser: true });

const articleSchema = new mongoose.Schema({
  title: String,
  content: String,
});
articleSchema.index({ title: 1 });
const Article = mongoose.model("Article", articleSchema);

const commentSchema = new mongoose.Schema({
  content: String,
  article: {
    type: mongoose.Schema.Types.ObjectId,
    ref: "Article",
  },
});

const Comment = mongoose.model("Comment", commentSchema);

app.get("/", (req, res) => {
  res.send("Hello World!");
});

app.get("/articles", async (req, res) => {
  const articles = await Article.find();
  res.json(articles);
});

app.get("/articles/:id", async (req, res) => {
  const article = await Article.findById(req.params.id);
  res.json(article);
});

app.post("/articles", async (req, res) => {
  const article = new Article(req.body);
  await article.save();
  res.json(article);
});

app.put("/articles/:id", async (req, res) => {
  const article = await Article.findByIdAndUpdate(req.params.id, req.body, {
    new: true,
  });
  res.json(article);
});

app.delete("/articles/:id", async (req, res) => {
  await Article.findByIdAndDelete(req.params.id);
  res.json({ message: "Deleted" });
});

app.get("/comments", async (req, res) => {
  const comments = await Comment.find();
  res.json(comments);
});

app.get("/comments/:id", async (req, res) => {
  const comment = await Comment.findById(req.params.id);
  res.json(comment);
});

app.post("/comments", async (req, res) => {
  const comment = new Comment(req.body);
  await comment.save();
  res.json(comment);
});

app.put("/comments/:id", async (req, res) => {
  const comment = await Comment.findByIdAndUpdate(req.params.id, req.body, {
    new: true,
  });
  res.json(comment);
});

app.delete("/comments/:id", async (req, res) => {
  await Comment.findByIdAndDelete(req.params.id);
  res.json({ message: "Deleted" });
});

app.listen(3000, () => {
  console.log("Server started");
});
