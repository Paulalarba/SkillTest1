using Microsoft.EntityFrameworkCore;
namespace Alarba.Models.Data
{
    public class ApplicationDbContext : DbContext
    { 
        public ApplicationDbContext(DbContextOptions<ApplicationDbContext> options)
            : base(options) { }
        public DbSet<Registrations> Registrations { get; set; }
        public DbSet<Participants> Participants { get; set; }
        public DbSet<EventTables> EventTables { get; set; }

    }
}

