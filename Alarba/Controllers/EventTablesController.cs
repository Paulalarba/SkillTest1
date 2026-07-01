
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using Alarba.Models;
using Alarba.Models.Data;

public class EventTablesController : Controller
{
    private readonly ApplicationDbContext _context;

    public EventTablesController(ApplicationDbContext context)
    {
        _context = context;
    }

    // GET: EVENTTABLESS
    public async Task<IActionResult> Index(string searchString)    
    {
        ViewData["CurrentFilter"] = searchString;

        var events = from e in _context.EventTables select e;

        if (!string.IsNullOrEmpty(searchString))
        {
            events = events.Where(e => e.eventName.Contains(searchString)
                                    || e.eventDate.Contains(searchString)
                                    || e.eventVenue.Contains(searchString));
        }

        return View(await events.ToListAsync());
    }

    // GET: EVENTTABLESS/Details/5
    public async Task<IActionResult> Details(int? id)
    {
        if (id == null)
        {
            return NotFound();
        }

        var eventtables = await _context.EventTables
            .FirstOrDefaultAsync(m => m.Id == id);
        if (eventtables == null)
        {
            return NotFound();
        }

        return View(eventtables);
    }

    // GET: EVENTTABLESS/Create
    public IActionResult Create()
    {
        return View();
    }

    // POST: EVENTTABLESS/Create
    // To protect from overposting attacks, enable the specific properties you want to bind to.
    // For more details, see http://go.microsoft.com/fwlink/?LinkId=317598.
    [HttpPost]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> Create([Bind("Id,evCode,eventName,eventDate,eventVenue,eventfee")] EventTables eventtables)
    {
        if (ModelState.IsValid)
        {
            _context.Add(eventtables);
            await _context.SaveChangesAsync();
            return RedirectToAction(nameof(Index));
        }
        return View(eventtables);
    }

    // GET: EVENTTABLESS/Edit/5
    public async Task<IActionResult> Edit(int? id)
    {
        if (id == null)
        {
            return NotFound();
        }

        var eventtables = await _context.EventTables.FindAsync(id);
        if (eventtables == null)
        {
            return NotFound();
        }
        return View(eventtables);
    }

    // POST: EVENTTABLESS/Edit/5
    // To protect from overposting attacks, enable the specific properties you want to bind to.
    // For more details, see http://go.microsoft.com/fwlink/?LinkId=317598.
    [HttpPost]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> Edit(int? id, [Bind("Id,evCode,eventName,eventDate,eventVenue,eventfee")] EventTables eventtables)
    {
        if (id != eventtables.Id)
        {
            return NotFound();
        }

        if (ModelState.IsValid)
        {
            try
            {
                _context.Update(eventtables);
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!EventTablesExists(eventtables.Id))
                {
                    return NotFound();
                }
                else
                {
                    throw;
                }
            }
            return RedirectToAction(nameof(Index));
        }
        return View(eventtables);
    }

    // GET: EVENTTABLESS/Delete/5
    public async Task<IActionResult> Delete(int? id)
    {
        if (id == null)
        {
            return NotFound();
        }

        var eventtables = await _context.EventTables
            .FirstOrDefaultAsync(m => m.Id == id);
        if (eventtables == null)
        {
            return NotFound();
        }

        return View(eventtables);
    }

    // POST: EVENTTABLESS/Delete/5
    [HttpPost, ActionName("Delete")]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> DeleteConfirmed(int? id)
    {
        var eventtables = await _context.EventTables.FindAsync(id);
        if (eventtables != null)
        {
            _context.EventTables.Remove(eventtables);
        }

        await _context.SaveChangesAsync();
        return RedirectToAction(nameof(Index));
    }

    private bool EventTablesExists(int? id)
    {
        return _context.EventTables.Any(e => e.Id == id);
    }
}
