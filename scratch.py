import re

file_path = "c:\\MAMP\\htdocs\\NEW_ASPIRE_HUB\\aspirehub\\resources\\views\\modules\\crm\\clients\\manage-clients.blade.php"
with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# Edit 1: Dynamic modal title
target_1 = """                                  <h3 class="text-base font-bold text-slate-900 dark:text-white">
                                      Configure Google Analytics 4
                                  </h3>"""
replacement_1 = """                                  <h3 class="text-base font-bold text-slate-900 dark:text-white">
                                      @if($activeConfigIntegrationId === 'ga4')
                                          Configure Google Analytics 4
                                      @elseif($activeConfigIntegrationId === 'gsc')
                                          Configure Google Search Console
                                      @elseif($activeConfigIntegrationId === 'gads')
                                          Configure Google Ads
                                      @else
                                          Configure Integration
                                      @endif
                                  </h3>"""
content = content.replace(target_1, replacement_1)

# Edit 2: Add GSC Site block
target_2 = """                                      <div class="mt-3 flex items-center justify-between text-xs p-2.5 bg-slate-50/50 dark:bg-slate-900/30 rounded-2xl border border-slate-200/50 dark:border-slate-850/50 mb-4">
                                          <span class="text-slate-455 dark:text-slate-550 font-medium">GA4 Property ID</span>
                                          <span class="font-bold text-slate-755 dark:text-slate-300">{{ $integration['property_id'] }}</span>
                                      </div>
                                  @endif
                              @endif"""
replacement_2 = """                                      <div class="mt-3 flex items-center justify-between text-xs p-2.5 bg-slate-50/50 dark:bg-slate-900/30 rounded-2xl border border-slate-200/50 dark:border-slate-850/50 mb-4">
                                          <span class="text-slate-455 dark:text-slate-550 font-medium">GA4 Property ID</span>
                                          <span class="font-bold text-slate-755 dark:text-slate-300">{{ $integration['property_id'] }}</span>
                                      </div>
                                  @endif
                              @endif

                              @if ($integration['id'] === 'gsc' && $isConnected)
                                  @if (!$integration['property_id'])
                                      <div class="mt-4 p-3 bg-indigo-50/30 dark:bg-indigo-950/10 rounded-xl border border-indigo-100/30 dark:border-indigo-900/20 mb-4">
                                          <span class="text-[10px] font-bold text-indigo-650 dark:text-indigo-400 block mb-1.5 uppercase tracking-wider">Select GSC Site</span>
                                          <div class="flex gap-2">
                                              <select wire:model.live="selectedPropertyId" wire:key="gsc-property-select-{{ $integration['id'] }}" class="flex-1 text-[11px] rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 py-1.5 px-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                                  <option value="">Select Site URL...</option>
                                                  @foreach ($this->getGSCSites() as $prop)
                                                      <option value="{{ $prop['id'] }}">{{ $prop['name'] }}</option>
                                                  @endforeach
                                              </select>
                                              <button type="button" wire:click="saveGSCSite('{{ $integration['id'] }}')" wire:key="gsc-property-save-btn-{{ $integration['id'] }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-750 text-white font-bold text-[10px] rounded-lg transition shrink-0">
                                                  Save
                                              </button>
                                          </div>
                                      </div>
                                  @else
                                      <div class="mt-3 flex items-center justify-between text-xs p-2.5 bg-slate-50/50 dark:bg-slate-900/30 rounded-2xl border border-slate-200/50 dark:border-slate-850/50 mb-4">
                                          <span class="text-slate-455 dark:text-slate-550 font-medium">GSC Site URL</span>
                                          <span class="font-bold text-slate-755 dark:text-slate-300">{{ $integration['property_id'] }}</span>
                                      </div>
                                  @endif
                              @endif"""
content = content.replace(target_2, replacement_2)

with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)

print("Blade file updated successfully!")
