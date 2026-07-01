
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using Alarba.Models;
using Alarba.Models.Data;

public class RegistrationsController : Controller
{
    private readonly ApplicationDbContext _context;

    public RegistrationsController(ApplicationDbContext context)
    {
        _context = context;
    }

    // GET: REGISTRATIONSS
    public async Task<IActionResult> Index(string searchString)    
    {
        ViewData["CurrentFilter"] = searchString;

        var registrations = from r in _context.Registrations select r;

        if (!string.IsNullOrEmpty(searchString))
        {
            registrations = registrations.Where(r => r.regDate.Contains(searchString)
                                                  || r.regPayOptions.Contains(searchString));
        }

        return View(await registrations.ToListAsync());
    }

    // GET: REGISTRATIONSS/Details/5
    public async Task<IActionResult> Details(int? id)
    {
        if (id == null)
        {
            return NotFound();
        }

        var registrations = await _context.Registrations
            .FirstOrDefaultAsync(m => m.Id == id);
        if (registrations == null)
        {
            return NotFound();
        }

        return View(registrations);
    }

    // GET: REGISTRATIONSS/Create
    public IActionResult Create()
    {
        return View();
    }

    // POST: REGISTRATIONSS/Create
    // To protect from overposting attacks, enable the specific properties you want to bind to.
    // For more details, see http://go.microsoft.com/fwlink/?LinkId=317598.
    [HttpPost]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> Create([Bind("Id,regId,RegistraionId,regDate,RegistrationFee,regPayOptions")] Registrations registrations)
    {
        if (ModelState.IsValid)
        {
            _context.Add(registrations);
            await _context.SaveChangesAsync();
            return RedirectToAction(nameof(Index));
        }
        return View(registrations);
    }

    // GET: REGISTRATIONSS/Edit/5
    public async Task<IActionResult> Edit(int? id)
    {
        if (id == null)
        {
            return NotFound();
        }

        var registrations = await _context.Registrations.FindAsync(id);
        if (registrations == null)
        {
            return NotFound();
        }
        return View(registrations);
    }

    // POST: REGISTRATIONSS/Edit/5
    // To protect from overposting attacks, enable the specific properties you want to bind to.
    // For more details, see http://go.microsoft.com/fwlink/?LinkId=317598.
    [HttpPost]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> Edit(int? id, [Bind("Id,regId,RegistraionId,regDate,RegistrationFee,regPayOptions")] Registrations registrations)
    {
        if (id != registrations.Id)
        {
            return NotFound();
        }

        if (ModelState.IsValid)
        {
            try
            {
                _context.Update(registrations);
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!RegistrationsExists(registrations.Id))
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
        return View(registrations);
    }

    // GET: REGISTRATIONSS/Delete/5
    public async Task<IActionResult> Delete(int? id)
    {
        if (id == null)
        {
            return NotFound();
        }

        var registrations = await _context.Registrations
            .FirstOrDefaultAsync(m => m.Id == id);
        if (registrations == null)
        {
            return NotFound();
        }

        return View(registrations);
    }

    // POST: REGISTRATIONSS/Delete/5
    [HttpPost, ActionName("Delete")]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> DeleteConfirmed(int? id)
    {
        var registrations = await _context.Registrations.FindAsync(id);
        if (registrations != null)
        {
            _context.Registrations.Remove(registrations);
        }

        await _context.SaveChangesAsync();
        return RedirectToAction(nameof(Index));
    }

    private bool RegistrationsExists(int? id)
    {
        return _context.Registrations.Any(e => e.Id == id);
    }
}
