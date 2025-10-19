import { useEffect, useState } from "react";
import api from "../api";
import { useNavigate } from "react-router-dom";


export default function Dashboard() {
  const [tasks, setTasks] = useState([]);
  const [newTask, setNewTask] = useState({
    title: "",
    description: "",
    due_date: "",
    priority: "low",
    assignee_email: "",
  });
  const nav = useNavigate();

  const fetchTasks = async () => {
    try {
      const res = await api.get("/tasks/index.php");
      setTasks(res.data);
    } catch (err) {
      if (err.response?.status === 401) {
        alert("Session expired. Please login again.");
        nav("/login");
      }
    }
  };

  const handleCreate = async () => {
    if (!newTask.title || !newTask.due_date || !newTask.assignee_email) {
      alert("Title, Due date, and Assignee email required");
      return;
    }
    await api.post("/tasks/index.php", newTask);
    setNewTask({ title: "", description: "", due_date: "", priority: "low", assignee_email: "" });
    fetchTasks();
  };

  const toggleComplete = async (task) => {
    await api.put("/tasks/index.php", {
      id: task.id,
      is_completed: task.is_completed ? 0 : 1,
    });
    fetchTasks();
  };

  const deleteTask = async (id) => {
    await api.delete("/tasks/index.php", { data: { id } });
    fetchTasks();
  };

  const getStatus = (task) => {
    const today = new Date().toISOString().split("T")[0];
    if (task.is_completed) return "✅ Done";
    if (task.due_date < today) return "❌ Missed";
    if (task.due_date === today) return "⚠️ Due Today";
    return "🕓 Upcoming";
  };

  useEffect(() => {
    fetchTasks();
  }, []);

  const logout = async () => {
    await api.get("/logout.php");
    localStorage.removeItem("user");
    nav("/login");
  };

  return (
    <div className="container mt-4">
      <div className="d-flex justify-content-between align-items-center mb-3">
        <h2>My Tasks</h2>
        <button className="btn btn-outline-danger" onClick={logout}>
          Logout
        </button>
      </div>

      <div className="card p-3 mb-4">
        <h5>Create Task</h5>
        <input
          placeholder="Title"
          className="form-control mb-2"
          value={newTask.title}
          onChange={(e) => setNewTask({ ...newTask, title: e.target.value })}
        />
        <textarea
          placeholder="Description"
          className="form-control mb-2"
          value={newTask.description}
          onChange={(e) => setNewTask({ ...newTask, description: e.target.value })}
        ></textarea>
        <input
          type="date"
          className="form-control mb-2"
          value={newTask.due_date}
          onChange={(e) => setNewTask({ ...newTask, due_date: e.target.value })}
        />
        <select
          className="form-select mb-2"
          value={newTask.priority}
          onChange={(e) => setNewTask({ ...newTask, priority: e.target.value })}
        >
          <option value="low">Low</option>
          <option value="medium">Medium</option>
          <option value="high">High</option>
        </select>
        <input
          placeholder="Assignee Email"
          className="form-control mb-2"
          value={newTask.assignee_email}
          onChange={(e) => setNewTask({ ...newTask, assignee_email: e.target.value })}
        />
        <button className="btn btn-primary w-100" onClick={handleCreate}>
          Add Task
        </button>
      </div>

      {tasks.length === 0 ? (
        <p>No tasks assigned yet.</p>
      ) : (
        <ul className="list-group">
          {tasks.map((t) => (
            <li key={t.id} className="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <strong>{t.title}</strong> — {getStatus(t)} <br />
                <small>
                  Due: {t.due_date} | Priority: {t.priority}
                </small>
              </div>
              <div>
                <button className="btn btn-sm btn-success me-2" onClick={() => toggleComplete(t)}>
                  {t.is_completed ? "Undo" : "Done"}
                </button>
                <button className="btn btn-sm btn-danger" onClick={() => deleteTask(t.id)}>
                  Delete
                </button>
              </div>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
