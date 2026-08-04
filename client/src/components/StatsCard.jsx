import React from 'react';

const StatsCard = ({ title, subtitle, number, label, icon: Icon, colorClass = 'cyan', contentItems }) => {
  const colorStyles = {
    jobs: 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30',
    applications: 'bg-amber-500/10 text-amber-400 border-amber-500/30',
    sectors: 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
    content: 'bg-purple-500/10 text-purple-400 border-purple-500/30'
  };

  const selectedStyle = colorStyles[colorClass] || colorStyles.jobs;

  return (
    <div className="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl hover:border-slate-700 transition-all">
      <div className="flex items-start justify-between mb-4">
        <div>
          <h3 className="text-lg font-bold text-white tracking-tight">{title}</h3>
          <p className="text-xs text-slate-400">{subtitle}</p>
        </div>
        <div className={`p-3 rounded-xl border ${selectedStyle}`}>
          {Icon && <Icon className="w-6 h-6" />}
        </div>
      </div>

      {contentItems ? (
        <div className="grid grid-cols-3 gap-2 pt-2 border-t border-slate-800/80">
          {contentItems.map((item, idx) => (
            <div key={idx} className="text-center p-2 rounded-lg bg-slate-950/60 border border-slate-800">
              <span className="block text-lg font-bold text-white">{item.number}</span>
              <span className="block text-[11px] text-slate-400 font-medium">{item.label}</span>
            </div>
          ))}
        </div>
      ) : (
        <div className="pt-2">
          <div className="text-3xl font-extrabold text-white tracking-tight">{number}</div>
          <div className="text-xs text-cyan-400 font-medium mt-1">{label}</div>
        </div>
      )}
    </div>
  );
};

export default StatsCard;
