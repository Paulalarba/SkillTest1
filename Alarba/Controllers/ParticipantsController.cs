
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using Alarba.Models;
using Alarba.Models.Data;

public class ParticipantsController : Controller
{
    private readonly ApplicationDbContext _context;

    public ParticipantsController(ApplicationDbContext context)
    {
        _context = context;
    }

    // GET: PARTICIPANTSS
    public async Task<IActionResult> Index(string searchString)    
    {
        ViewData["CurrentFilter"] = searchString;

        var participants = from p in _context.Participants select p;

        if (!string.IsNullOrEmpty(searchString))
        {
            participants = participants.Where(p => p.lastName.Contains(searchString)
                                                || p.firstname.Contains(searchString));
        }

        return View(await participants.ToListAsync());
    }

    // GET: PARTICIPANTSS/Details/5
    public async Task<IActionResult> Details(int? id)
    {
        if (id == null)
        {
            return NotFound();
        }

        var participants = await _context.Participants
            .FirstOrDefaultAsync(m => m.Id == id);
        if (participants == null)
        {
            return NotFound();
        }

        return View(participants);
    }

    // GET: PARTICIPANTSS/Create
    public IActionResult Create()
    {
        return View();
    }

    // POST: PARTICIPANTSS/Create
    // To protect from overposting attacks, enable the specific properties you want to bind to.
    // For more details, see http://go.microsoft.com/fwlink/?LinkId=317598.
    [HttpPost]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> Create([Bind("Id,partId,evCode,lastName,firstname,Discount")] Participants participants)
    {
        if (ModelState.IsValid)
        {
            _context.Add(participants);
            await _context.SaveChangesAsync();
            return RedirectToAction(nameof(Index));
        }
        return View(participants);
    }

    // GET: PARTICIPANTSS/Edit/5
    public async Task<IActionResult> Edit(int? id)
    {
        if (id == null)
        {
            return NotFound();
        }

        var participants = await _context.Participants.FindAsync(id);
        if (participants == null)
        {
            return NotFound();
        }
        return View(participants);
    }

    // POST: PARTICIPANTSS/Edit/5
    // To protect from overposting attacks, enable the specific properties you want to bind to.
    // For more details, see http://go.microsoft.com/fwlink/?LinkId=317598.
    [HttpPost]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> Edit(int? id, [Bind("Id,partId,evCode,lastName,firstname,Discount")] Participants participants)
    {
        if (id != participants.Id)
        {
            return NotFound();
        }

        if (ModelState.IsValid)
        {
            try
            {
                _context.Update(participants);
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!ParticipantsExists(participants.Id))
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
        return View(participants);
    }

    // GET: PARTICIPANTSS/Delete/5
    public async Task<IActionResult> Delete(int? id)
    {
        if (id == null)
        {
            return NotFound();
        }

        var participants = await _context.Participants
            .FirstOrDefaultAsync(m => m.Id == id);
        if (participants == null)
        {
            return NotFound();
        }

        return View(participants);
    }

    // POST: PARTICIPANTSS/Delete/5
    [HttpPost, ActionName("Delete")]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> DeleteConfirmed(int? id)
    {
        var participants = await _context.Participants.FindAsync(id);
        if (participants != null)
        {
            _context.Participants.Remove(participants);
        }

        await _context.SaveChangesAsync();
        return RedirectToAction(nameof(Index));
    }

    private bool ParticipantsExists(int? id)
    {
        return _context.Participants.Any(e => e.Id == id);
    }
}
